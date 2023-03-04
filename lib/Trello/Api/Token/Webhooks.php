<?php

namespace Trello\Api\Token;

use Trello\Api\AbstractApi;

/**
 * Trello Token Webhooks API
 * @link https://trello.com/docs/api/token
 *
 * Fully implemented.
 */
class Webhooks extends AbstractApi
{
    protected string $path = 'tokens/#id#/webhooks';

    /**
     * Get webhooks related to a given token
     * @link https://trello.com/docs/api/token/#get-1-tokens-token-webhooks
     *
     * @param string $userToken the token's id
     * @param array $params optional parameters
     *
     * @return array
     */
    public function all(string $userToken, array $params = []): array
    {
        return $this->get($this->getPath($userToken), $params);
    }

    /**
     * Get a webhook
     * @link https://trello.com/docs/api/token/#get-1-tokens-token-webhooks-idwebhook
     *
     * @param string $userToken the token's id
     * @param string $webhookId the webhook's id
     *
     * @return array
     */
    public function show(string $userToken, string $webhookId): array
    {
        return $this->get($this->getPath($userToken) . '/' . rawurlencode($webhookId));
    }

    /**
     * Create a webhook
     * @link https://trello.com/docs/api/token/#post-1-tokens-token-webhooks
     *
     * @param string $webhookId the id of the token the webhook should be created on
     * @param array $params optional attributes
     *
     * @return array card info
     */
    public function create(string $webhookId, array $params): array
    {
        $this->validateRequiredParameters(['callbackURL', 'idModel'], $params);

        return $this->post($this->getPath($webhookId), $params);
    }

    /**
     * Update a webhook
     * @link https://trello.com/docs/api/token/#put-1-tokens-token-webhooks
     *
     * @param string $webhookId the id of the token the webhook is attached to
     * @param array $params optional attributes
     *
     * @return array card info
     */
    public function update(string $webhookId, array $params): array
    {
        $this->validateRequiredParameters(['callbackURL', 'idModel'], $params);

        return $this->put($this->getPath($webhookId), $params);
    }

    /**
     * Remove a webhook
     * @link https://trello.com/docs/api/token/#delete-1-tokens-token-webhooks-idwebhook
     *
     * @param string $userToken the id of the token the webhook is attached to
     * @param string $webhookId id of the webhook to remove
     *
     * @return array card info
     */
    public function remove(string $userToken, string $webhookId): array
    {
        return $this->delete($this->getPath($userToken) . '/' . rawurlencode($webhookId));
    }
}
