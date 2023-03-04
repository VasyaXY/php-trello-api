<?php

namespace Trello\Api\Member;

use Trello\Api\AbstractApi;

/**
 * Trello Member Cards API
 * @link https://trello.com/docs/api/member
 *
 * Fully implemented.
 */
class Cards extends AbstractApi
{
    protected string $path = 'members/#id#/cards';

    /**
     * Get cards related to a given list
     * @link https://trello.com/docs/api/member/#get-1-members-idmember-or-username-cards
     *
     * @param string $id the member's id or username
     * @param array $params optional parameters
     *
     * @return array
     */
    public function all(string $id, array $params = []): array
    {
        return $this->get($this->getPath($id), $params);
    }

    /**
     * Filter cards related to a given list
     * @link https://trello.com/docs/api/list/#get-1-lists-idlist-cards-filter
     *
     * @param string $id the list's id
     * @param string|array $filter one of 'none', 'visible', 'open', 'closed', 'all'
     *
     * @return array
     */
    public function filter(string $id, string|array $filter = 'all'): array
    {
        $allowed = ['none', 'visible', 'open', 'closed', 'all'];
        $filters = $this->validateAllowedParameters($allowed, $filter, 'filter');

        return $this->get($this->getPath($id) . '/' . implode(',', $filters));
    }
}
