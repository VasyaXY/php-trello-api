<?php

namespace Trello\Api\Card;

use Trello\Api\AbstractApi;

/**
 * Trello Card Actions API
 * @link https://trello.com/docs/api/card
 *
 * Fully implemented.
 */
class Actions extends AbstractApi
{
    protected string $path = 'cards/#id#/actions';

    /**
     * Get actions related to a given card
     * @link https://trello.com/docs/api/card/#get-1-cards-card-id-or-shortlink-actions
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
     * Add comment to a given card
     * @link https://trello.com/docs/api/card/#post-1-cards-card-id-or-shortlink-actions-comments
     *
     * @param string $id the card's id or short link
     * @param string $text comment message
     *
     * @return array
     */
    public function addComment(string $id, string $text): array
    {
        return $this->post($this->getPath($id) . '/comments', ['text' => $text]);
    }

    /**
     * Remove comment to a given card
     * @link https://trello.com/docs/api/card/#delete-1-cards-card-id-or-shortlink-actions-idaction-comments
     *
     * @param string $id the card's id or short link
     * @param string $commentId the comment's id
     *
     * @return array
     */
    public function removeComment(string $id, string $commentId): array
    {
        return $this->delete($this->getPath($id) . '/comments/' . rawurlencode($commentId));
    }
}
