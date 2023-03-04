<?php

namespace Trello\Api;

/**
 * Trello Token API
 * @link https://trello.com/docs/api/token
 *
 * Fully implemented.
 */
class Token extends AbstractApi
{
    /**
     * Base path of tokens api
     * @var string
     */
    protected string $path = 'tokens';

    /**
     * Token fields
     * @link https://trello.com/docs/api/token/#get-1-tokens-token-field
     * @var array
     */
    public static array $fields = [
        'identifier',
        'idMember',
        'dateCreated',
        'dateExpires',
        'permissions'
    ];

    /**
     * Find a token by id
     * @link https://trello.com/docs/api/token/#get-1-tokens-idtoken
     *
     * @param string $id the token's id
     * @param array $params optional attributes
     *
     * @return array
     */
    public function show(string $id, array $params = []): array
    {
        return $this->get($this->getPath() . '/' . rawurlencode($id), $params);
    }

    /**
     * Remove a token
     * @link https://trello.com/docs/api/token/#delete-1-tokens-idtoken
     *
     * @param string $id the token's id
     *
     * @return array
     */
    public function remove(string $id): array
    {
        return $this->delete($this->getPath() . '/' . rawurlencode($id));
    }

    /**
     * Get a given token's member
     * @link https://trello.com/docs/api/token/#get-1-tokens-token-member
     *
     * @param string $id the token's id
     * @param array $params optional parameters
     *
     * @return array
     */
    public function getMember(string $id, array $params = []): array
    {
        return $this->get($this->getPath() . '/' . rawurlencode($id) . '/member', $params);
    }

    /**
     * Get a given token's member's field
     * @link https://trello.com/docs/api/token/#get-1-tokens-token-member-field
     *
     * @param string $id the token's id
     *
     * @return array
     */
    public function getMemberField(string $id, string|array $field): array
    {
        $this->validateAllowedParameters(Member::$fields, $field, 'field');

        return $this->get($this->getPath() . '/' . rawurlencode($id) . '/member/' . rawurlencode($field));
    }

    /**
     * Webhooks API
     *
     * @return Token\Webhooks
     */
    public function webhooks(): Token\Webhooks
    {
        return new Token\Webhooks($this->client);
    }
}
