<?php

namespace Trello\Api\Card;

use Trello\Api\AbstractApi;
use Trello\Exception\InvalidArgumentException;

/**
 * Trello Card Members API
 * @link https://trello.com/docs/api/card
 *
 * Fully implemented.
 */
class Members extends AbstractApi
{
    protected string $path = 'cards/#id#/members';

    /**
     * Get members related to a given card
     * @link https://trello.com/docs/api/card/#get-1-cards-card-id-or-shortlink-members
     *
     * @param string $id the card's id or short link
     * @param array $params optional parameters
     *
     * @return array
     */
    public function all(string $id, array $params = []): array
    {
        return $this->get($this->getPath($id), $params);
    }

    /**
     * Set members of a given card
     * @link https://trello.com/docs/api/card/#put-1-cards-card-id-or-shortlink-idmembers
     *
     * @param string $id the card's id or short link
     * @param array $members An array of member ids
     *
     * @return array
     */
    public function set(string $id, array $members): array
    {
        if (!count($members)) {
            throw new InvalidArgumentException('You must specify at least one member id.');
        }

        $members = implode(',', $members);

        return $this->put($this->getPath($id), ['value' => $members]);
    }

    /**
     * Add a member to a given card
     * @link https://trello.com/docs/api/card/#post-1-cards-card-id-or-shortlink-idmembers
     *
     * @param string $id the card's id or short link
     * @param string $memberId the member's id
     *
     * @return array
     */
    public function add(string $id, string $memberId): array
    {
        return $this->post($this->getPath($id), ['value' => $memberId]);
    }

    /**
     * Remove a given member from a given card
     * @link https://trello.com/docs/api/card/#delete-1-cards-card-id-or-shortlink-idmembers-idmember
     *
     * @param string $id the card's id or short link
     * @param string $memberId the members's id
     *
     * @return array
     */
    public function remove(string $id, string $memberId): array
    {
        return $this->delete($this->getPath($id) . '/' . rawurlencode($memberId));
    }

    /**
     * Add a given member's vote to a given card
     * @link https://trello.com/docs/api/card/#post-1-cards-card-id-or-shortlink-membersvoted
     *
     * @param string $id the card's id or short link
     * @param string $memberId the members's id
     *
     * @return array
     */
    public function addVote(string $id, string $memberId): array
    {
        return $this->post($this->getPath($id) . '/membersVoted', ['value' => $memberId]);
    }

    /**
     * Remove a given member's vote from a given card
     * @link https://trello.com/docs/api/card/#delete-1-cards-card-id-or-shortlink-membersvoted-idmember
     *
     * @param string $id the card's id or short link
     * @param string $memberId the members's id
     *
     * @return array
     */
    public function removeVote(string $id, string $memberId): array
    {
        return $this->delete($this->getPath($id) . '/membersVoted/' . rawurlencode($memberId));
    }
}
