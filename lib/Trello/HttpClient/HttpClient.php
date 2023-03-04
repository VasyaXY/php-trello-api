<?php

namespace Trello\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Trello\Exception\ErrorException;
use Trello\Exception\RuntimeException;

class HttpClient implements HttpClientInterface
{
    protected const API_VERSION = 1;

    protected array $options = [
        'base_uri' => 'https://api.trello.com/' . self::API_VERSION . '/',
        RequestOptions::HTTP_ERRORS => false,
        RequestOptions::TIMEOUT => 10,
    ];

    /**
     * @var ClientInterface
     */
    protected ClientInterface $client;

    protected array $headers = [];

    /**
     * @var \GuzzleHttp\HandlerStack
     */
    protected HandlerStack $handlerStack;

    /**
     * @param array $options
     * @param \GuzzleHttp\ClientInterface|null $client
     */
    public function __construct(array $options = [], ClientInterface $client = null)
    {
        $this->options = array_merge($this->options, $options);

        $this->handlerStack = HandlerStack::create();
        $this->handlerStack->push(Middleware::error(), 'trello_error');

        $this->client = $client ?: new GuzzleClient(['handler' => $this->handlerStack]);

        $this->clearHeaders();
    }

    /**
     * {@inheritDoc}
     */
    public function setOption(string $name, mixed $value): void
    {
        $this->options[$name] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Clears used headers
     */
    public function clearHeaders(): void
    {
        $this->headers = [
            'Accept' => sprintf('application/vnd.orcid.%s+json', self::API_VERSION),
        ];
    }

    /**
     * Build a handler stack.
     *
     * @return \GuzzleHttp\HandlerStack
     */
    public function getHandlerStack(): HandlerStack
    {
        return $this->handlerStack;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $path, array $parameters = [], array $headers = []): Response|ResponseInterface
    {
        return $this->request('GET', $path, $headers, [
            RequestOptions::QUERY => $parameters,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function post(string $path, mixed $body = null, array $headers = []): Response|ResponseInterface
    {
        return $this->request('POST', $path, $headers, [
            RequestOptions::FORM_PARAMS => $body,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function patch(string $path, mixed $body = null, array $headers = []): Response|ResponseInterface
    {
        return $this->request('PATCH', $path, $headers, [
            RequestOptions::FORM_PARAMS => $body,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $path, mixed $body = null, array $headers = []): Response|ResponseInterface
    {
        return $this->request('DELETE', $path, $headers, [
            RequestOptions::FORM_PARAMS => $body,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function put(string $path, mixed $body, array $headers = []): Response|ResponseInterface
    {
        return $this->request('PUT', $path, $headers, [
            RequestOptions::FORM_PARAMS => $body,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function request(string $httpMethod = 'GET', string $path = '', array $headers = [], array $options = []): Response|ResponseInterface
    {
        $options['headers'] = array_merge($this->headers, $headers);
        $options = array_merge($this->options, $options);

        try {
            $response = $this->client->request($httpMethod, $path, $options);
        } catch (\LogicException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode(), $e);
        } catch (\RuntimeException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function authenticate(string $tokenOrLogin, string|null $password, string|null $authMethod): void
    {
        $this->handlerStack->push(Middleware::authenticate($tokenOrLogin, $password, $authMethod), 'trello_authenticate');
    }
}
