<?php

namespace Trello\Api;

use Trello\Api\Checklist\Cards;
use Trello\Api\Checklist\Items;
use Trello\Exception\InvalidArgumentException;

/**
 * Trello Checkist API
 * @link https://trello.com/docs/api/checklist
 *
 * Fully implemented.
 */
class Checklist extends AbstractApi
{
    /**
     * Base path of checklists api
     * @var string
     */
    protected string $path = 'checklists';

    /**
     * Card fields
     * @link https://trello.com/docs/api/list/#get-1-lists-list-id-or-shortlink-field
     * @var array
     */
    public static array $fields = [
        'name',
        'idBoard',
        'idCard',
        'pos',
    ];

    /**
     * Find a list by id
     * @link https://trello.com/docs/api/checklist/#get-1-checklists-idchecklist
     *
     * @param string $id the checklist's id
     * @param array $params optional attributes
     *
     * @return array list info
     */
    public function show(string $id, array $params = []): array
    {
        return $this->get($this->getPath() . '/' . rawurlencode($id), $params);
    }

    /**
     * Create a checklist
     * @link https://trello.com/docs/api/checklist/#post-1-checklists
     *
     * @param array $params optional attributes: 'name', 'idBoard', 'idCard', 'pos', 'idChecklistSource'
     *
     * @return array
     */
    public function create(array $params = []): array
    {
        $this->validateRequiredParameters(['name', 'idCard'], $params);

        return $this->post($this->getPath(), $params);
    }

    /**
     * Update a checklist
     * @link https://trello.com/docs/api/checklist/#put-1-checklists-idchecklist
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
     * Remove a checklist
     * @link https://trello.com/docs/api/checklist/#delete-1-checklists-idchecklist
     *
     * @param string $id the checklist's id
     *
     * @return array
     */
    public function remove(string $id): array
    {
        return $this->delete($this->getPath() . '/' . rawurlencode($id));
    }

    /**
     * Get the board of a given checklist
     * @link https://trello.com/docs/api/checklist/#get-1-checklists-idchecklist-board
     *
     * @param string $id the checklist's id
     * @param array $params optional parameters
     *
     * @return array board info
     */
    public function getBoard(string $id, array $params = []): array
    {
        return $this->get($this->getPath() . '/' . rawurlencode($id) . '/board', $params);
    }

    /**
     * Get the field of a board of a given checklist
     * @link https://trello.com/docs/api/checklist/#get-1-checklists-idchecklist-board-field
     *
     * @param string $id the checklist's id
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
     * Set a given checklist's card
     * @link https://trello.com/docs/api/checklist/#put-1-checklists-idchecklist-idcard
     *
     * @param string $id the list's id
     * @param string $cardId the card's id
     *
     * @return array
     */
    public function setCard(string $id, string $cardId): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/idCard', ['value' => $cardId]);
    }

    /**
     * Set a given checklist's name
     * @link https://trello.com/docs/api/checklist/#put-1-checklists-idchecklist-name
     *
     * @param string $id the checklist's id
     * @param string $name the name
     *
     * @return array
     */
    public function setName(string $id, string $name): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/name', ['value' => $name]);
    }

    /**
     * Set a given checklist's position
     * @link https://trello.com/docs/api/checklist/#put-1-checklists-idchecklist-pos
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
     * Cards API
     *
     * @return Checklist\Cards
     */
    public function cards(): Cards
    {
        return new Checklist\Cards($this->client);
    }

    /**
     * Items API
     *
     * @return Checklist\Items
     */
    public function items(): Items
    {
        return new Checklist\Items($this->client);
    }
}
