<?php

namespace Trello\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\RequestOptions;
use Trello\Exception\ErrorException;
use Trello\Exception\RuntimeException;

class HttpClient implements HttpClientInterface
{
    protected const API_VERSION = 1;

    protected $options = [
        'base_uri' => 'https://api.trello.com/' . self::API_VERSION . '/',
        RequestOptions::HTTP_ERRORS => false,
        RequestOptions::TIMEOUT => 10,
    ];

    /**
     * @var ClientInterface
     */
    protected $client;

    protected $headers = [];

    /**
     * @var \GuzzleHttp\HandlerStack
     */
    protected $handlerStack;

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
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Clears used headers
     */
    public function clearHeaders()
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
    public function getHandlerStack()
    {
        return $this->handlerStack;
    }

    /**
     * {@inheritDoc}
     */
    public function get($path, array $parameters = [], array $headers = [])
    {
        return $this->request('GET', $path, $headers, [
            RequestOptions::QUERY => $parameters,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function post($path, $body = null, array $headers = [])
    {
        return $this->request('POST', $path, $headers, [
            RequestOptions::FORM_PARAMS => $body,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function patch($path, $body = null, array $headers = [])
    {
        return $this->request('PATCH', $path, $headers, [
            RequestOptions::FORM_PARAMS => $body,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($path, $body = null, array $headers = [])
    {
        return $this->request('DELETE', $path, $headers, [
            RequestOptions::FORM_PARAMS => $body,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function put($path, $body, array $headers = [])
    {
        return $this->request('PUT', $path, $headers, [
            RequestOptions::FORM_PARAMS => $body,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function request($httpMethod = 'GET', $path = '', array $headers = [], array $options = [])
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
    public function authenticate($tokenOrLogin, $password, $authMethod)
    {
        $this->handlerStack->push(Middleware::authenticate($tokenOrLogin, $password, $authMethod), 'trello_authenticate');
    }
}
