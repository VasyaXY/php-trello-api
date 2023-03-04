<?php

namespace Trello;

use League\OAuth1\Client\Credentials\TemporaryCredentials;
use League\OAuth1\Client\Credentials\TokenCredentials;
use League\OAuth1\Client\Server\Trello as OAuthServer;
use Trello\Api\Action;
use Trello\Api\ApiInterface;
use Trello\Api\Board;
use Trello\Api\Card;
use Trello\Api\CardList;
use Trello\Api\Checklist;
use Trello\Api\Label;
use Trello\Api\Member;
use Trello\Api\Notification;
use Trello\Api\Organization;
use Trello\Api\Token;
use Trello\Api\Webhook;
use Trello\Exception\BadMethodCallException;
use Trello\Exception\InvalidArgumentException;
use Trello\HttpClient\HttpClient;
use Trello\HttpClient\HttpClientInterface;
use vasyaxy\Services\Trello\Configuration;

/**
 * Simple PHP Trello client
 *
 * @method Api\Action action()
 * @method Api\Action actions()
 * @method Api\Board board()
 * @method Api\Board boards()
 * @method Api\Card card()
 * @method Api\Card cards()
 * @method Api\Checklist checklist()
 * @method Api\Checklist checklists()
 * @method Api\CardList list()
 * @method Api\CardList lists()
 * @method Api\Organization organization()
 * @method Api\Organization organizations()
 * @method Api\Member member()
 * @method Api\Member members()
 * @method Api\Token token()
 * @method Api\Token tokens()
 * @method Api\Webhook webhook()
 * @method Api\Webhook webhooks()
 */
class Client implements ClientInterface
{
    /**
     * Constant for authentication method. Indicates the default, but deprecated
     * login with username and token in URL.
     */
    const AUTH_URL_TOKEN = 'url_token';

    /**
     * Constant for authentication method. Not indicates the new login, but allows
     * usage of unauthenticated rate limited requests for given client_id + client_secret
     */
    const AUTH_URL_CLIENT_ID = 'url_client_id';

    /**
     * Constant for authentication method. Indicates the new favored login method
     * with username and password via HTTP Authentication.
     */
    const AUTH_HTTP_PASSWORD = 'http_password';

    /**
     * Constant for authentication method. Indicates the new login method with
     * with username and token via HTTP Authentication.
     */
    const AUTH_HTTP_TOKEN = 'http_token';

    var TemporaryCredentials|null $TemporaryCredentials = null;

    /**
     * @var array
     */
    private array $options = [
        'base_url' => 'https://api.trello.com/',
        'user_agent' => 'php-trello-api',
        'timeout' => 10,
        'api_limit' => 5000,
        'api_version' => 1,
        'cache_dir' => null,
    ];

    private array $authOptions = [
        'identifier' => '',
        'secret' => '',
        'callback_uri' => '',
        'name' => 'Trello Google GoogleCalendar',
        'expiration' => 'never',
        'scope' => 'read,write',
    ];

    /**
     * The Buzz instance used to communicate with Trello
     *
     * @var HttpClientInterface
     */
    private HttpClientInterface $httpClient;
    private OAuthServer|null $OAuthServer = null;

    /**
     * Instantiate a new Trello client
     *
     * @param null|HttpClientInterface $httpClient Trello http client
     */
    public function __construct(HttpClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?? new HttpClient();
    }

    /**
     * Get an API by name
     *
     * @param string $name
     *
     * @return ApiInterface
     *
     * @throws InvalidArgumentException if the requested api does not exist
     */
    public function api(string $name): ApiInterface
    {
        switch ($name) {
            case 'action':
            case 'actions':
                $api = new Api\Action($this);
                break;
            case 'board':
            case 'boards':
                $api = new Api\Board($this);
                break;
            case 'card':
            case 'cards':
                $api = new Api\Card($this);
                break;
            case 'checklist':
            case 'checklists':
                $api = new Api\Checklist($this);
                break;
            case 'list':
            case 'lists':
            case 'cardlist':
            case 'cardlists':
                $api = new Api\CardList($this);
                break;
            case 'member':
            case 'members':
                $api = new Api\Member($this);
                break;
            case 'notification':
            case 'notifications':
                $api = new Api\Notification($this);
                break;
            case 'organization':
            case 'organizations':
                $api = new Api\Organization($this);
                break;
            case 'token':
            case 'tokens':
                $api = new Api\Token($this);
                break;
            case 'webhook':
            case 'webhooks':
                $api = new Api\Webhook($this);
                break;
            case 'label':
            case 'labels':
                $api = new Api\Label($this);
                break;
            default:
                throw new InvalidArgumentException(sprintf('Undefined api called: "%s"', $name));
        }

        return $api;
    }


    public function apiAction(): Action
    {
        return new Api\Action($this);
    }

    public function apiBoard(): Board
    {
        return new Api\Board($this);
    }

    public function apiCard(): Card
    {
        return new Api\Card($this);
    }

    public function apiChecklist(): Checklist
    {
        return new Api\Checklist($this);
    }

    public function apiCardList(): CardList
    {
        return new Api\CardList($this);
    }

    public function apiMember(): Member
    {
        return new Api\Member($this);
    }

    public function apiNotification(): Notification
    {
        return new Api\Notification($this);
    }

    public function apiOrganization(): Organization
    {
        return new Api\Organization($this);
    }

    public function apiToken(): Token
    {
        return new Api\Token($this);
    }

    public function apiWebhook(): Webhook
    {
        return new Api\Webhook($this);
    }

    public function apiLabel(): Label
    {
        return new Api\Label($this);
    }

    /**
     * Authenticate a user for all next requests
     *
     * @param string $tokenOrLogin Trello private token/username/client ID
     * @param null|string $password Trello password/secret (optionally can contain $authMethod)
     * @param null|string $authMethod One of the AUTH_* class constants
     *
     * @throws InvalidArgumentException If no authentication method was given
     */
    public function authenticate(string $tokenOrLogin, string|null $password = null, string|null $authMethod = null): self
    {
        if (null === $password && null === $authMethod) {
            throw new InvalidArgumentException('You need to specify authentication method!');
        }

        $valid = [
            self::AUTH_URL_TOKEN,
            self::AUTH_URL_CLIENT_ID,
            self::AUTH_HTTP_PASSWORD,
            self::AUTH_HTTP_TOKEN
        ];
        if (null === $authMethod && in_array($password, $valid)) {
            $authMethod = $password;
            $password = null;
        }

        if (null === $authMethod) {
            $authMethod = self::AUTH_HTTP_PASSWORD;
        }

        $this->getHttpClient()->authenticate($tokenOrLogin, $password, $authMethod);

        return $this;
    }

    /**
     * Get Http Client
     *
     * @return HttpClient
     */
    public function getHttpClient(): HttpClient
    {
        if (null === $this->httpClient) {
            $this->httpClient = new HttpClient($this->options);
        }

        return $this->httpClient;
    }

    /**
     * Set Http Client
     *
     * @param HttpClientInterface $httpClient
     */
    public function setHttpClient(HttpClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * Clears used headers
     */
    public function clearHeaders(): self
    {
        $this->getHttpClient()->clearHeaders();
        return $this;
    }

    /**
     * Set headers
     *
     * @param array $headers
     */
    public function setHeaders(array $headers): self
    {
        $this->getHttpClient()->setHeaders($headers);
        return $this;
    }

    /**
     * Get option by name
     *
     * @param string $name the option's name
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function getOption(string $name): mixed
    {
        if (!array_key_exists($name, $this->options)) {
            throw new InvalidArgumentException(sprintf('Undefined option called: "%s"', $name));
        }

        return $this->options[$name];
    }

    /**
     * Get option by name
     *
     * @param string $name the option's name
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function getAuthOption(string $name): mixed
    {
        if (!array_key_exists($name, $this->authOptions)) {
            throw new InvalidArgumentException(sprintf('Undefined auth option called: "%s"', $name));
        }

        return $this->authOptions[$name];
    }

    /**
     * Set option
     *
     * @param string $name
     * @param mixed $value
     *
     * @throws InvalidArgumentException if the option is not defined
     * @throws InvalidArgumentException if the api version is set to an unsupported one
     */
    public function setOption(string $name, mixed $value): self
    {
        if (!array_key_exists($name, $this->options)) {
            throw new InvalidArgumentException(sprintf('Undefined option called: "%s"', $name));
        }

        if ('api_version' == $name && !in_array($value, $this->getSupportedApiVersions())) {
            throw new InvalidArgumentException(sprintf('Invalid API version ("%s"), valid are: %s', $name, implode(', ', $supportedApiVersions)));
        }

        $this->options[$name] = $value;
        return $this;
    }

    /**
     * Set option
     *
     * @param string $name
     * @param mixed $value
     *
     * @throws InvalidArgumentException if the option is not defined
     * @throws InvalidArgumentException if the api version is set to an unsupported one
     */
    public function setAuthOption(string $name, mixed $value): self
    {
        if (!array_key_exists($name, $this->authOptions)) {
            throw new InvalidArgumentException(sprintf('Undefined auth option called: "%s"', $name));
        }

        $this->authOptions[$name] = $value;
        return $this;
    }

    /**
     * Returns an array of valid API versions supported by this client.
     *
     * @return integer[]
     */
    public function getSupportedApiVersions(): array
    {
        return [1];
    }

    private function getOAuthServer(): OAuthServer
    {
        if (!$this->OAuthServer) {
            $this->OAuthServer = new OAuthServer($this->authOptions);
        }

        return $this->OAuthServer;
    }

    public function getTemporaryCredentials(): TemporaryCredentials|null
    {
        if (!$this->TemporaryCredentials)
            $this->TemporaryCredentials = $this->getOAuthServer()->getTemporaryCredentials();

        return $this->TemporaryCredentials;
    }

    public function setTemporaryCredentials(TemporaryCredentials $TemporaryCredentials): self
    {
        $this->TemporaryCredentials = $TemporaryCredentials;
        return $this;
    }

    public function getAuthUrl(array $options = []): string
    {
        return $this->getOAuthServer()->getAuthorizationUrl($this->getTemporaryCredentials());
    }

    public function getAccessToken(string $oAuthToken, string $oAuthVerifier): TokenCredentials
    {
        return $this->getOAuthServer()->getTokenCredentials(
            $this->getTemporaryCredentials(),
            $oAuthToken,
            $oAuthVerifier
        );
    }

    /**
     * Proxies $this->members() to $this->api('members')
     *
     * @param string $name method name
     * @param array $args arguments
     *
     * @return ApiInterface
     *
     * @throws BadMethodCallException
     */
    public function __call(string $name, array $args): ApiInterface
    {
        try {
            return $this->api($name);
        } catch (InvalidArgumentException $e) {
            throw new BadMethodCallException(sprintf('Undefined method called: "%s"', $name));
        }
    }
}
