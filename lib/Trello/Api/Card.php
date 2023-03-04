<?php

namespace Trello\Api;

use Trello\Api\Card\Actions;
use Trello\Api\Card\Attachments;
use Trello\Api\Card\Checklists;
use Trello\Api\Card\Labels;
use Trello\Api\Card\Members;
use Trello\Api\Card\Stickers;
use Trello\Exception\InvalidArgumentException;

/**
 * Trello Card API
 * @link https://trello.com/docs/api/card
 *
 * Unimplemented:
 * - https://trello.com/docs/api/card/#put-1-cards-card-id-or-shortlink-checklist-idchecklist-checkitem-idcheckitem-name
 * - https://trello.com/docs/api/card/#put-1-cards-card-id-or-shortlink-checklist-idchecklist-checkitem-idcheckitem-pos
 * - https://trello.com/docs/api/card/#put-1-cards-card-id-or-shortlink-checklist-idchecklist-checkitem-idcheckitem-state
 * - https://trello.com/docs/api/card/#put-1-cards-card-id-or-shortlink-checklist-idchecklistcurrent-checkitem-idcheckitem
 * - https://trello.com/docs/api/card/#post-1-cards-card-id-or-shortlink-checklist-idchecklist-checkitem
 * - https://trello.com/docs/api/card/#post-1-cards-card-id-or-shortlink-checklist-idchecklist-checkitem-idcheckitem-converttocard
 * - https://trello.com/docs/api/card/#post-1-cards-card-id-or-shortlink-markassociatednotificationsread
 */
class Card extends AbstractApi
{
    /**
     * Base path of cards api
     * @var string
     */
    protected string $path = 'cards';

    /**
     * Card fields
     * @link https://trello.com/docs/api/card/#get-1-cards-card-id-or-shortlink-field
     * @var array
     */
    public static array $fields = [
        'badges',
        'checkItemStates',
        'closed',
        'dateLastActivity',
        'desc',
        'descData',
        'due',
        'email',
        'idBoard',
        'idChecklists',
        'idList',
        'idMembers',
        'idMembersVoted',
        'idShort',
        'idAttachmentCover',
        'manualCoverAttachment',
        'labels',
        'name',
        'pos',
        'shortLink',
        'shortUrl',
        'subscribed',
        'url',
    ];

    /**
     * Find a card by id
     * @link https://trello.com/docs/api/card/#get-1-cards-card-id-or-shortlink
     *
     * @param string $id the card's id or short link
     * @param array $params optional attributes
     *
     * @return array card info
     */
    public function show(string $id, array $params = []): array
    {
        return $this->get($this->getPath() . '/' . rawurlencode($id), $params);
    }

    /**
     * Create a card
     * @link https://trello.com/docs/api/card/#post-1-cards
     *
     * @param array $params optional attributes
     *
     * @return array card info
     */
    public function create(array $params = []): array
    {
        $this->validateRequiredParameters(['idList', 'name'], $params);

        if (!array_key_exists('due', $params)) {
            $params['due'] = null;
        }
        if (!array_key_exists('urlSource', $params)) {
            $params['urlSource'] = null;
        }

        return $this->post($this->getPath(), $params);
    }

    /**
     * Update a card
     * @link https://trello.com/docs/api/card/#put-1-cards
     *
     * @param string $id the card's id or short link
     * @param array $params card attributes to update
     *
     * @return array card info
     */
    public function update(string $id, array $params = []): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id), $params);
    }

    /**
     * Set a given card's board
     * @link https://trello.com/docs/api/card/#put-1-cards-card-id-or-shortlink-idboard
     *
     * @param string $id the card's id or short link
     * @param string $boardId the board's id
     *
     * @return array board info
     */
    public function setBoard(string $id, string $boardId): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/idBoard', ['value' => $boardId]);
    }

    /**
     * Get a given card's board
     * @link https://trello.com/docs/api/card/#put-1-cards-card-id-or-shortlink-idboard
     *
     * @param string $id the card's id or short link
     * @param array $params optional parameters
     *
     * @return array board info
     */
    public function getBoard(string $id, array $params = []): array
    {
        return $this->get($this->getPath() . '/' . rawurlencode($id) . '/board', $params);
    }

    /**
     * Get the field of a board of a given card
     * @link https://trello.com/docs/api/card/#get-1-cards-card-id-or-shortlink-board-field
     *
     * @param string $id the card's id
     * @param array $field the name of the field
     *
     * @return array board info
     *
     * @throws InvalidArgumentException if the field does not exist
     */
    public function getBoardField(string $id, array $field): array
    {
        $this->validateAllowedParameters(Board::$fields, $field, 'field');

        return $this->get($this->getPath() . '/' . rawurlencode($id) . '/board/' . rawurlencode($field));
    }

    /**
     * Set a given card's list
     * @link https://trello.com/docs/api/card/#put-1-cards-card-id-or-shortlink-idlist
     *
     * @param string $id the card's id or short link
     * @param string $listId the list's id
     *
     * @return array list info
     */
    public function setList(string $id, string $listId): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/idList', ['value' => $listId]);
    }

    /**
     * Get a given card's list
     * @link https://trello.com/docs/api/card/#get-1-cards-card-id-or-shortlink-list
     *
     * @param string $id the card's id or short link
     * @param array $params optional parameters
     *
     * @return array list info
     */
    public function getList(string $id, array $params = []): array
    {
        return $this->get($this->getPath() . '/' . rawurlencode($id) . '/list', $params);
    }

    /**
     * Get the field of a list of a given card
     * @link https://trello.com/docs/api/card/#get-1-cards-card-id-or-shortlink-list-field
     *
     * @param string $id the card's id
     * @param array $field the name of the field
     *
     * @return array board info
     *
     * @throws InvalidArgumentException if the field does not exist
     */
    public function getListField(string $id, array $field): array
    {
        $this->validateAllowedParameters(CardList::$fields, $field, 'field');

        return $this->get($this->getPath() . '/' . rawurlencode($id) . '/list/' . rawurlencode($field));
    }

    /**
     * Set a given card's name
     * @link https://trello.com/docs/api/card/#put-1-cards-card-id-name
     *
     * @param string $id the card's id or short link
     * @param string $name the name
     *
     * @return array card info
     */
    public function setName(string $id, string $name): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/name', ['value' => $name]);
    }

    /**
     * Set a given card's description
     * @link https://trello.com/docs/api/card/#put-1-cards-card-id-desc
     *
     * @param string $id the card's id or short link
     * @param string $description the description
     *
     * @return array card info
     */
    public function setDescription(string $id, string $description): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/desc', ['value' => $description]);
    }

    /**
     * Set a given card's state
     * @link https://trello.com/docs/api/card/#put-1-cards-card-id-closed
     *
     * @param string $id the card's id or short link
     * @param bool $closed whether the card should be closed or not
     *
     * @return array card info
     */
    public function setClosed(string $id, bool $closed = true): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/closed', ['value' => $closed]);
    }

    /**
     * Set a given card's due date
     * @link https://trello.com/docs/api/card/#put-1-cards-card-id-or-shortlink-due
     *
     * @param string $id the card's id or short link
     * @param \DateTime $date the due date
     *
     * @return array card info
     */
    public function setDueDate(string $id, \DateTime $date = null): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/due', ['value' => $date]);
    }

    /**
     * Set a given card's position
     * @link https://trello.com/docs/api/card/#put-1-cards-card-id-or-shortlink-pos
     *
     * @param string $id the card's id or short link
     * @param string|integer $position the position, eg. 'top', 'bottom'
     *                                 or a positive number
     *
     * @return array card info
     */
    public function setPosition(string $id, string|int $position): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/pos', ['value' => $position]);
    }

    /**
     * Set a given card's subscription state
     * @link https://trello.com/docs/api/card/#put-1-cards-card-id-or-shortlink-subscribed
     *
     * @param string $id the list's id
     * @param bool $subscribed subscription state
     *
     * @return array list info
     */
    public function setSubscribed(string $id, bool $subscribed): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/subscribed', ['value' => $subscribed]);
    }

    /**
     * Actions API
     *
     * @return Card\Actions
     */
    public function actions(): Actions
    {
        return new Card\Actions($this->client);
    }

    /**
     * Attachments API
     *
     * @return Card\Attachments
     */
    public function attachments(): Attachments
    {
        return new Card\Attachments($this->client);
    }

    /**
     * Checklists API
     *
     * @return Card\Checklists
     */
    public function checklists(): Checklists
    {
        return new Card\Checklists($this->client);
    }

    /**
     * Labels API
     *
     * @return Card\Labels
     */
    public function labels(): Labels
    {
        return new Card\Labels($this->client);
    }

    /**
     * Members API
     *
     * @return Card\Members
     */
    public function members(): Members
    {
        return new Card\Members($this->client);
    }

    /**
     * Stickers API
     *
     * @return Card\Stickers
     */
    public function stickers(): Stickers
    {
        return new Card\Stickers($this->client);
    }
}
