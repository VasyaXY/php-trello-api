<?php

namespace Trello\HttpClient;

use GuzzleHttp\Psr7\Query;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Trello\Client;
use Trello\Exception\ApiLimitExceedException;
use Trello\Exception\ErrorException;
use Trello\Exception\PermissionDeniedException;
use Trello\Exception\RuntimeException;
use Trello\Exception\ValidationFailedException;
use Trello\HttpClient\Message\ResponseMediator;

class Middleware
{
    /**
     * @return callable
     */
    public static function error(): callable
    {
        return static function (callable $handler) {
            return static function (RequestInterface $request, array $options) use ($handler) {
                return $handler($request, $options)->then(
                    function (ResponseInterface $response) {
                        if ($response->getStatusCode() < 400) {
                            return $response;
                        }

                        if (429 === $response->getStatusCode()) {
                            throw new ApiLimitExceedException('Wait a second.', 429);
                        }

                        $content = ResponseMediator::getContent($response);
                        if (is_array($content) && isset($content['message'])) {
                            if (400 === $response->getStatusCode()) {
                                throw new ErrorException($content['message'], 400);
                            }

                            if (401 === $response->getStatusCode()) {
                                throw new PermissionDeniedException($content['message'], 401);
                            }

                            if (422 === $response->getStatusCode() && isset($content['errors'])) {
                                $errors = [];
                                foreach ($content['errors'] as $error) {
                                    switch ($error['code']) {
                                        case 'missing':
                                            $errors[] = sprintf('The %s %s does not exist, for resource "%s"', $error['field'], $error['value'], $error['resource']);
                                            break;

                                        case 'missing_field':
                                            $errors[] = sprintf('Field "%s" is missing, for resource "%s"', $error['field'], $error['resource']);
                                            break;

                                        case 'invalid':
                                            $errors[] = sprintf('Field "%s" is invalid, for resource "%s"', $error['field'], $error['resource']);
                                            break;

                                        case 'already_exists':
                                            $errors[] = sprintf('Field "%s" already exists, for resource "%s"', $error['field'], $error['resource']);
                                            break;

                                        default:
                                            $errors[] = $error['message'];
                                            break;

                                    }
                                }

                                throw new ValidationFailedException('Validation Failed: ' . implode(', ', $errors), 422);
                            }
                        }

                        throw new RuntimeException($content['message'] ?? $content, $response->getStatusCode());
                    }
                );
            };
        };
    }

    /**
     * @param $tokenOrLogin
     * @param $password
     * @param $authMethod
     * @return callable
     */
    public static function authenticate($tokenOrLogin, $password, $authMethod): callable
    {
        return static function (callable $handler) use ($tokenOrLogin, $password, $authMethod) {
            return static function (RequestInterface $request, array $options) use ($handler, $tokenOrLogin, $password, $authMethod) {
                if (null === $authMethod) {
                    return $handler($request, $options);
                }

                $password = $password ?: null;

                switch ($authMethod) {
                    case Client::AUTH_HTTP_PASSWORD:
                        $request->withHeader(
                            'Authorization',
                            sprintf('Basic %s', base64_encode($tokenOrLogin . ':' . $password))
                        );
                        break;
                    case Client::AUTH_HTTP_TOKEN:
                        $request->withHeader(
                            'Authorization',
                            sprintf('token %s', $tokenOrLogin)
                        );
                        break;

                    case Client::AUTH_URL_CLIENT_ID:
                        $query = Query::parse($request->getUri()->getQuery());
                        $query = array_merge($query, [
                            'key'   => $tokenOrLogin,
                            'token' => $password,
                        ]);
                        $query = http_build_query($query, '', '&', PHP_QUERY_RFC3986);
                        $request = $request->withUri($request->getUri()->withQuery($query));
                        break;

                    case Client::AUTH_URL_TOKEN:
                        $query = Query::parse($request->getUri()->getQuery());
                        $query = array_merge($query, [
                            'token' => $tokenOrLogin,
                            'key' => $password,
                        ]);
                        $query = http_build_query($query, '', '&', PHP_QUERY_RFC3986);
                        $request = $request->withUri($request->getUri()->withQuery($query));
                        break;

                    default:
                        throw new RuntimeException(sprintf('%s not yet implemented', $authMethod));
                }

                return $handler($request, $options);
            };
        };
    }
}
