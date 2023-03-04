<?php

namespace Trello\HttpClient;

use Psr\Http\Message\ResponseInterface;
use Trello\Exception\InvalidArgumentException;
use GuzzleHttp\Psr7\Response;

interface HttpClientInterface
{
    /**
     * Send a GET request
     *
     * @param string $path Request path
     * @param array $parameters GET Parameters
     * @param array $headers Reconfigure the request headers for this call only
     *
     * @return Response|ResponseInterface
     */
    public function get(string $path, array $parameters = [], array $headers = []): Response|ResponseInterface;

    /**
     * Send a POST request
     *
     * @param string $path Request path
     * @param mixed $body Request body
     * @param array $headers Reconfigure the request headers for this call only
     *
     * @return Response|ResponseInterface
     */
    public function post(string $path, mixed $body = null, array $headers = []): Response|ResponseInterface;

    /**
     * Send a PATCH request
     *
     * @param string $path Request path
     * @param mixed $body Request body
     * @param array $headers Reconfigure the request headers for this call only
     *
     * @return Response|ResponseInterface
     * @internal param array $parameters Request body
     */
    public function patch(string $path, mixed $body = null, array $headers = []): Response|ResponseInterface;

    /**
     * Send a PUT request
     *
     * @param string $path Request path
     * @param mixed $body Request body
     * @param array $headers Reconfigure the request headers for this call only
     *
     * @return Response|ResponseInterface
     */
    public function put(string $path, mixed $body, array $headers = []): Response|ResponseInterface;

    /**
     * Send a DELETE request
     *
     * @param string $path Request path
     * @param mixed $body Request body
     * @param array $headers Reconfigure the request headers for this call only
     *
     * @return Response|ResponseInterface
     */
    public function delete(string $path, mixed $body = null, array $headers = []): Response|ResponseInterface;

    /**
     * Send a request to the server, receive a response,
     * decode the response and returns an associative array
     *
     * @param string $httpMethod HTTP method to use
     * @param string $path Request path
     * @param array $headers Request headers
     * @param array $options Request options
     *
     * @return Response|ResponseInterface
     */
    public function request(string $httpMethod = 'GET', string $path = '', array $headers = [], array $options = []): Response|ResponseInterface;

    /**
     * Change an option value.
     *
     * @param string $name The option name
     * @param mixed $value The value
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function setOption(string $name, mixed $value): void;

    /**
     * Set HTTP headers
     *
     * @param array $headers
     *
     * @return void
     */
    public function setHeaders(array $headers): void;

    /**
     * Authenticate a user for all next requests
     *
     * @param string $tokenOrLogin Trello private token/username/client ID
     * @param null|string $password Trello password/secret (optionally can contain $authMethod)
     * @param null|string $authMethod One of the AUTH_* class constants
     *
     * @return void
     * @throws InvalidArgumentException If no authentication method was given
     */
    public function authenticate(string $tokenOrLogin, string|null $password, string|null $authMethod): void;
}
