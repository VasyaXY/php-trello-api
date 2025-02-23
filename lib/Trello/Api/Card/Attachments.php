<?php

namespace Trello\Api\Card;

use Trello\Api\AbstractApi;

/**
 * Trello Card Attachments API
 * @link https://trello.com/docs/api/card
 *
 * Fully implemented.
 */
class Attachments extends AbstractApi
{
    protected string $path = 'cards/#id#/attachments';

    /**
     * Get attachments related to a given card
     * @link https://trello.com/docs/api/card/#get-1-cards-card-id-or-shortlink-attachments
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
     * Add an attachment to a given card
     * @link https://trello.com/docs/api/card/#post-1-cards-card-id-or-shortlink-attachments
     *
     * @param string $id the card's id or short link
     * @param array $params optional parameters
     *
     * @return array
     */
    public function create(string $id, array $params): array
    {
        $atLeastOneOf = ['url', 'file'];
        $this->validateAtLeastOneOf($atLeastOneOf, $params);

        return $this->post($this->getPath($id), $params);
    }

    /**
     * Get a given attachment on a given card
     * @link https://trello.com/docs/api/card/#get-1-cards-card-id-or-shortlink-attachments-idattachment
     *
     * @param string $id the card's id or short link
     * @param string $attachmentId the attachment's id
     *
     * @return array
     */
    public function show(string $id, string $attachmentId): array
    {
        return $this->get($this->getPath($id) . '/' . rawurlencode($attachmentId));
    }

    /**
     * Remove a given attachment from a given card
     * @link https://trello.com/docs/api/card/#delete-1-cards-card-id-or-shortlink-attachments-idattachment
     *
     * @param string $id the card's id or short link
     * @param string $attachmentId the attachment's id
     *
     * @return array
     */
    public function remove(string $id, string $attachmentId): array
    {
        return $this->delete($this->getPath($id) . '/' . rawurlencode($attachmentId));
    }

    /**
     * Set a given attachment as cover of a given card
     * @link https://trello.com/docs/api/card/#put-1-cards-card-id-or-shortlink-idattachmentcover
     *
     * @param string $id the card's id or short link
     * @param string $attachmentId the attachment's id
     *
     * @return array
     */
    public function setAsCover(string $id, string $attachmentId): array
    {
        return $this->put('cards/' . rawurlencode($id) . '/idAttachmentCover', ['value' => $attachmentId]);
    }
}
