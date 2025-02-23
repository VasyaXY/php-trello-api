<?php

namespace Trello\Api;

use Trello\Exception\InvalidArgumentException;

/**
 * Trello List API
 * @link https://trello.com/docs/api/list
 *
 * Fully implemented.
 */
class CardList extends AbstractApi
{
    /**
     * Base path of lists api
     * @var string
     */
    protected string $path = 'lists';

    /**
     * List fields
     * @link https://trello.com/docs/api/list/#get-1-lists-list-id-or-shortlink-field
     * @var array
     */
    public static array $fields = [
        'name',
        'closed',
        'idBoard',
        'pos',
        'subscribed',
    ];

    /**
     * Find a list by id
     * @link https://trello.com/docs/api/list/#get-1-lists-idlist
     *
     * @param string $id the list's id
     * @param array $params optional attributes
     *
     * @return array list info
     */
    public function show(string $id, array $params = []): array
    {
        return $this->get($this->getPath() . '/' . rawurlencode($id), $params);
    }

    /**
     * Create a list
     * @link https://trello.com/docs/api/list/#post-1-lists
     *
     * @param array $params Required attributes: 'name', 'idBoard'
     *                      Optional attributes: 'pos'
     *
     * @return array
     */
    public function create(array $params = []): array
    {
        $this->validateRequiredParameters(['name', 'idBoard'], $params);

        return $this->post($this->getPath(), $params);
    }

    /**
     * Update a list
     * @link https://trello.com/docs/api/list/#put-1-lists-idlist
     *
     * @param string $id the list's id
     * @param array $params list attributes to update
     *
     * @return array list info
     */
    public function update(string $id, array $params = []): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id), $params);
    }

    /**
     * Set a given list's board
     * @link https://trello.com/docs/api/list/#put-1-lists-idlist-idboard
     *
     * @param string $id the list's id
     * @param string $boardId the board's id
     *
     * @return array board info
     */
    public function setBoard(string $id, string $boardId): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/idBoard', ['value' => $boardId]);
    }

    /**
     * Get a given list's board
     * @link https://trello.com/docs/api/list/#get-1-lists-idlist-board
     *
     * @param string $id the list's id
     * @param array $params optional parameters
     *
     * @return array board info
     */
    public function getBoard(string $id, array $params = []): array
    {
        return $this->get($this->getPath() . '/' . rawurlencode($id) . '/board', $params);
    }

    /**
     * Get the field of a board of a given list
     * @link https://trello.com/docs/api/list/#get-1-lists-idlist-board-field
     *
     * @param string $id the list's id
     * @param array $field the name of the field
     *
     * @return array
     *
     * @throws InvalidArgumentException if the field does not exist
     */
    public function getBoardField(string $id, array $field): array
    {
        $this->validateAllowedParameters(Board::$fields, $field, 'field');

        return $this->get($this->getPath() . '/' . rawurlencode($id) . '/board/' . rawurlencode($field));
    }

    /**
     * Set a given list's name
     * @link https://trello.com/docs/api/list/#put-1-lists-idlist-name
     *
     * @param string $id the list's id
     * @param string $name the name
     *
     * @return array list info
     */
    public function setName(string $id, string $name): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/name', ['value' => $name]);
    }

    /**
     * Set a given list's description
     * @link https://trello.com/docs/api/list/#put-1-lists-list-id-desc
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
     * Set a given list's state
     * @link https://trello.com/docs/api/list/#put-1-lists-idlist-closed
     *
     * @param string $id the list's id
     * @param bool $closed whether the list should be closed or not
     *
     * @return array list info
     */
    public function setClosed(string $id, bool $closed = true): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/closed', ['value' => $closed]);
    }

    /**
     * Set a given list's position
     * @link https://trello.com/docs/api/list/#put-1-lists-idlist-pos
     *
     * @param string $id the list's id
     * @param string|integer $position the position, eg. 'top', 'bottom'
     *                                 or a positive number
     *
     * @return array list info
     */
    public function setPosition(string $id, string|int $position): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/pos', ['value' => $position]);
    }

    /**
     * Actions API
     *
     * @return Cardlist\Actions
     */
    public function actions(): Cardlist\Actions
    {
        return new Cardlist\Actions($this->client);
    }

    /**
     * Cards API
     *
     * @return Cardlist\Cards
     */
    public function cards(): Cardlist\Cards
    {
        return new Cardlist\Cards($this->client);
    }
}
