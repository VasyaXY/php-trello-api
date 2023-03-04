<?php

namespace Trello\Api;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Trello\Client;
use Trello\HttpClient\Message\ResponseMediator;
use Trello\Exception\InvalidArgumentException;
use Trello\Exception\BadMethodCallException;
use Trello\Exception\MissingArgumentException;
use \DateTime;

/**
 * Abstract class for Api classes
 *
 * @author Christian Daguerre <christian.daguerre@gmail.com>
 * @author Joseph Bielawski <stloyd@gmail.com>
 */
abstract class AbstractApi implements ApiInterface
{
    /**
     * API sub namespace
     *
     * @var string
     */
    protected string $path;

    /**
     * The client
     *
     * @var Client
     */
    protected Client $client;

    /**
     * @var array
     */
    public static array $fields = [];

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Catches any undefined "get{$field}" calls, and passes them
     * to the getField() if the $field is in the $this->fields property
     *
     * @param string $method called method
     * @param array $arguments array of arguments passed to called method
     *
     * @return array
     *
     * @throws BadMethodCallException If the method does not start with "get"
     *                                or the field is not included in the $fields property
     */
    public function __call(string $method, array $arguments): array
    {
        if (isset($this->fields) && substr($method, 0, 3) === 'get') {
            $property = lcfirst(substr($method, 3));
            if (in_array($property, $this->fields) && count($arguments) === 2) {
                return $this->getField($arguments[0], $arguments[1]);
            }
        }

        throw new BadMethodCallException(sprintf(
            'There is no method named "%s" in class "%s".',
            $method,
            get_called_class()
        ));
    }

    /**
     * Get field names (properties)
     *
     * @return array array of fields
     */
    public function getFields(): array
    {
        return static::$fields;
    }

    /**
     * Get a field value by field name
     *
     * @param string $id the board's id
     * @param string $field the field
     *
     * @return mixed field value
     *
     * @throws InvalidArgumentException If the field does not exist
     */
    public function getField(string $id, string $field): mixed
    {
        if (!in_array($field, static::$fields)) {
            throw new InvalidArgumentException(sprintf('There is no field named %s.', $field));
        }

        $response = $this->get($this->path . '/' . rawurlencode($id) . '/' . rawurlencode($field));

        return $response['_value'] ?? $response;
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param string $path Request path.
     * @param array $parameters GET parameters.
     * @param array $requestHeaders Request Headers.
     *
     * @return mixed
     */
    protected function get(string $path, array $parameters = [], array $requestHeaders = []): mixed
    {
        $response = $this->client->getHttpClient()->get($path, $parameters, $requestHeaders);

        return ResponseMediator::getContent($response);
    }

    /**
     * Send a HEAD request with query parameters
     *
     * @param string $path Request path.
     * @param array $parameters HEAD parameters.
     * @param array $requestHeaders Request headers.
     *
     * @return Response|ResponseInterface
     */
    protected function head($path, array $parameters = [], array $requestHeaders = []): Response|ResponseInterface
    {
        return $this->client->getHttpClient()->request('HEAD', $path, $requestHeaders, [
            'query' => $parameters,
        ]);
    }

    /**
     * Send a POST request with JSON-encoded parameters.
     *
     * @param string $path Request path.
     * @param array $parameters POST parameters to be JSON encoded.
     * @param array $requestHeaders Request headers.
     *
     * @return mixed
     */
    protected function post(string $path, array $parameters = [], array $requestHeaders = []): mixed
    {
        return $this->postRaw(
            $path,
            $this->createParametersBody($parameters),
            $requestHeaders
        );
    }

    /**
     * Send a POST request with raw data.
     *
     * @param string $path Request path.
     * @param mixed $body Request body.
     * @param array $requestHeaders Request headers.
     *
     * @return mixed
     */
    protected function postRaw(string $path, mixed $body, array $requestHeaders = []): mixed
    {
        $response = $this->client->getHttpClient()->post(
            $path,
            $body,
            $requestHeaders
        );

        return ResponseMediator::getContent($response);
    }

    /**
     * Send a PATCH request with JSON-encoded parameters.
     *
     * @param string $path Request path.
     * @param array $parameters POST parameters to be JSON encoded.
     * @param array $requestHeaders Request headers.
     *
     * @return mixed
     */
    protected function patch(string $path, array $parameters = [], array $requestHeaders = []): mixed
    {
        $response = $this->client->getHttpClient()->patch(
            $path,
            $this->createParametersBody($parameters),
            $requestHeaders
        );

        return ResponseMediator::getContent($response);
    }

    /**
     * Send a PUT request with JSON-encoded parameters.
     *
     * @param string $path Request path.
     * @param array $parameters POST parameters to be JSON encoded.
     * @param array $requestHeaders Request headers.
     *
     * @return mixed
     */
    protected function put(string $path, array $parameters = [], array $requestHeaders = []): mixed
    {
        foreach ($parameters as $name => $parameter) {
            if (is_bool($parameter)) {
                $parameters[$name] = $parameter ? 'true' : 'false';
            }
        }

        $response = $this->client->getHttpClient()->put(
            $path,
            $this->createParametersBody($parameters),
            $requestHeaders
        );

        return ResponseMediator::getContent($response);
    }

    /**
     * Send a DELETE request with JSON-encoded parameters.
     *
     * @param string $path Request path.
     * @param array $parameters POST parameters to be JSON encoded.
     * @param array $requestHeaders Request headers.
     *
     * @return mixed
     */
    protected function delete(string $path, array $parameters = [], array $requestHeaders = []): mixed
    {
        $response = $this->client->getHttpClient()->delete(
            $path,
            $this->createParametersBody($parameters),
            $requestHeaders
        );

        return ResponseMediator::getContent($response);
    }

    /**
     * Prepare request parameters.
     *
     * @param array $parameters Request parameters
     *
     * @return null|array
     */
    protected function createParametersBody(array $parameters): array
    {
        foreach ($parameters as $name => $parameter) {
            if (is_bool($parameter)) {
                $parameters[$name] = $parameter ? 'true' : 'false';
            } elseif (is_array($parameter)) {
                foreach ($parameter as $subName => $subParameter) {
                    if (is_bool($subParameter)) {
                        $subParameter = $subParameter ? 'true' : 'false';
                    }
                    $parameters[$name . '/' . $subName] = $subParameter;
                }
                unset($parameters[$name]);
            } elseif ($parameter instanceof DateTime) {
                $parameters[$name] = $parameter->format($parameter::ATOM);
            }
        }

        return $parameters;
    }

    protected function getPath($id = null): string
    {
        if ($id) {
            return preg_replace('/\#id\#/', $id, $this->path);
        }

        return $this->path;
    }

    /**
     * Validate parameters array
     *
     * @param string[] $required required properties (array keys)
     * @param array $params array to check for existence of the required keys
     *
     * @throws MissingArgumentException if a required parameter is missing
     */
    protected function validateRequiredParameters(array $required, array $params): void
    {
        foreach ($required as $param) {
            if (!isset($params[$param])) {
                throw new MissingArgumentException(sprintf('The "%s" parameter is required.', $param));
            }
        }
    }

    /**
     * Validate allowed parameters array
     * Checks whether the passed parameters are allowed
     *
     * @param string[] $allowed allowed properties
     * @param array|string $params array to check
     * @param string $paramName
     *
     * @return array array of validated parameters
     *
     * @throws InvalidArgumentException if a parameter is not allowed
     */
    protected function validateAllowedParameters(array $allowed, array|string $params, string $paramName): array
    {
        if (!is_array($params)) {
            $params = [$params];
        }

        foreach ($params as $param) {
            if (!in_array($param, $allowed)) {
                throw new InvalidArgumentException(sprintf(
                    'The "%s" parameter may contain only values within "%s". "%s" given.',
                    $paramName,
                    implode(", ", $allowed),
                    $param
                ));
            }
        }

        return $params;
    }

    /**
     * Validate that the params array includes at least one of
     * the keys in a given array
     *
     * @param string[] $atLeastOneOf allowed properties
     * @param array $params array to check
     *
     * @return boolean
     *
     * @throws MissingArgumentException
     */
    protected function validateAtLeastOneOf(array $atLeastOneOf, array $params): bool
    {
        foreach ($atLeastOneOf as $param) {
            if (isset($params[$param])) {
                return true;
            }
        }

        throw new MissingArgumentException(sprintf(
            'You need to provide at least one of the following parameters "%s".',
            implode('", "', $atLeastOneOf)
        ));
    }
}
