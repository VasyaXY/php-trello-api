<?php

namespace Trello\Api;

use Trello\Api\Board\Actions;
use Trello\Api\Board\Cardlists;
use Trello\Api\Board\Cards;
use Trello\Api\Board\Checklists;
use Trello\Api\Board\Labels;
use Trello\Api\Board\Members;
use Trello\Api\Board\Memberships;
use Trello\Api\Board\MyPreferences;
use Trello\Api\Board\PowerUps;
use Trello\Api\Board\Preferences;

/**
 * Trello Board API
 * @package PHP Trello API
 * @category API
 * @link https://trello.com/docs/api/board
 *
 * Not implemented:
 * - Board my preferences API @see Board\MyPreferences
 * - Board preferences API @see Board\Preferences
 * - Board power ups API @see Board\PowerUps
 * - Board memberships API @see Board\Memberships
 * - https://trello.com/docs/api/board/#post-1-boards-board-id-calendarkey-generate
 * - https://trello.com/docs/api/board/#post-1-boards-board-id-emailkey-generate
 */
class Board extends AbstractApi
{
    /**
     * Base path of boards api
     * @var string
     */
    protected string $path = 'boards';

    /**
     * Board fields
     * @link https://trello.com/docs/api/board/#get-1-boards-board-id-field
     * @var array
     */
    public static array $fields = [
        'name',
        'desc',
        'descData',
        'closed',
        'idOrganization',
        'invited',
        'pinned',
        'starred',
        'url',
        'prefs',
        'invitations',
        'memberships',
        'shortLink',
        'subscribed',
        'labelNames',
        'powerUps',
        'dateLastActivity',
        'dateLastView',
        'shortUrl',
    ];

    /**
     * Find a board by id
     * @link https://trello.com/docs/api/board/#get-1-boards-board-id
     *
     * @param string $id the board's id
     * @param array $params optional attributes
     *
     * @return array board info
     */
    public function show(string $id, array $params = []): array
    {
        return $this->get($this->getPath() . '/' . rawurlencode($id), $params);
    }

    /**
     * Create a board
     * @link https://trello.com/docs/api/board/#post-1-boards
     *
     * @param array $params attributes
     *
     * @return array board info
     */
    public function create(array $params = []): array
    {
        $this->validateRequiredParameters(['name'], $params);

        return $this->post($this->getPath(), $params);
    }

    /**
     * Update a board
     * @link https://trello.com/docs/api/board/#put-1-boards
     *
     * @param string $id the board's id
     * @param array $params board attributes to update
     *
     * @return array
     */
    public function update(string $id, array $params = []): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id), $params);
    }

    /**
     * Set a given board's name
     * @link https://trello.com/docs/api/board/#put-1-boards-board-id-name
     *
     * @param string $id the board's id
     * @param string $name the name
     *
     * @return array
     */
    public function setName(string $id, string $name): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/name', ['value' => $name]);
    }

    /**
     * Set a given board's description
     * @link https://trello.com/docs/api/board/#put-1-boards-board-id-desc
     *
     * @param string $id the board's id
     * @param string $description the description
     *
     * @return array
     */
    public function setDescription(string $id, string $description): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/desc', ['value' => $description]);
    }

    /**
     * Set a given board's state
     * @link https://trello.com/docs/api/board/#put-1-boards-board-id-closed
     *
     * @param string $id the board's id
     * @param bool $closed whether the board should be closed or not
     *
     * @return array
     */
    public function setClosed(string $id, bool $closed = true): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/closed', ['value' => $closed]);
    }

    /**
     * Set a given board's subscription state
     * @link https://trello.com/docs/api/board/#put-1-boards-board-id-subscribed
     *
     * @param string $id the board's id
     * @param bool $subscribed whether to subscribe to the board or not
     *
     * @return array
     */
    public function setSubscribed(string $id, bool $subscribed = true): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/subscribed', ['value' => $subscribed]);
    }

    /**
     * Set a given board's organization
     * @link https://trello.com/docs/api/board/#put-1-boards-board-id-organization
     *
     * @param string $id the board's id
     * @param string $organizationId the organization's id
     *
     * @return array
     */
    public function setOrganization(string $id, string $organizationId): array
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/idOrganization/' . rawurlencode($organizationId));
    }

    /**
     * Get a given board's organization
     * @link https://trello.com/docs/api/board/#get-1-boards-board-id-organization
     *
     * @param string $id the board's id
     * @param array $params optional parameters
     *
     * @return array
     */
    public function getOrganization(string $id, array $params = []): array
    {
        return $this->get($this->getPath() . '/' . rawurlencode($id) . '/organization', $params);
    }

    /**
     * Get the field of the organization of a given board
     * @link https://trello.com/docs/api/board/#get-1-boards-board-id-organization-field
     *
     * @param string $id the board's id
     * @param string $field the organization's field name
     *
     * @return array
     */
    public function getOrganizationField(string $id, string $field): array
    {
        $this->validateAllowedParameters(Organization::$fields, $field, 'field');

        return $this->get($this->getPath() . '/' . rawurlencode($id) . '/organization/' . rawurlencode($field));
    }

    /**
     * Get a given board's stars
     * @link https://trello.com/docs/api/board/#get-1-boards-board-id-boardstars
     *
     * @param string $id the board's id
     * @param array $params optional parameters
     *
     * @return array
     */
    public function getStars(string $id, array $params = []): array
    {
        return $this->get($this->getPath() . '/' . rawurlencode($id) . '/boardStars', $params);
    }

    /**
     * Get a given board's deltas
     * @link https://trello.com/docs/api/board/index.html#get-1-boards-board-id-deltas
     *
     * @param string $id the board's id
     * @param array $params optional parameters
     *
     * @return array
     */
    public function getDeltas(string $id, array $params = []): array
    {
        return $this->get($this->getPath() . '/' . rawurlencode($id) . '/deltas', $params);
    }

    /**
     * Mark a given board as viewed
     * @link https://trello.com/docs/api/board/#post-1-boards-board-id-markasviewed
     *
     * @param string $id the board's id
     *
     * @return array
     */
    public function setViewed(string $id): array
    {
        return $this->post($this->getPath() . '/' . rawurlencode($id) . '/markAsViewed');
    }

    /**
     * Board Actions API
     *
     * @return Board\Actions
     */
    public function actions(): Actions
    {
        return new Board\Actions($this->client);
    }

    /**
     * Board Lists API
     *
     * @return Board\Cardlists
     */
    public function lists(): Cardlists
    {
        return new Board\Cardlists($this->client);
    }

    /**
     * Board Cards API
     *
     * @return Board\Cards
     */
    public function cards(): Cards
    {
        return new Board\Cards($this->client);
    }

    /**
     * Board Checklists API
     *
     * @return Board\Checklists
     */
    public function checklists(): Checklists
    {
        return new Board\Checklists($this->client);
    }

    /**
     * Board Labels API
     *
     * @return Board\Labels
     */
    public function labels(): Labels
    {
        return new Board\Labels($this->client);
    }

    /**
     * Board Members API
     *
     * @return Board\Members
     */
    public function members(): Members
    {
        return new Board\Members($this->client);
    }

    /**
     * Board Memberships API
     *
     * @return Board\Memberships
     */
    public function memberships(): Memberships
    {
        return new Board\Memberships($this->client);
    }

    /**
     * Board Preferences API
     *
     * @return Board\Preferences
     */
    public function preferences(): Preferences
    {
        return new Board\Preferences($this->client);
    }

    /**
     * Board MyPreferences API
     *
     * @return Board\MyPreferences
     */
    public function myPreferences(): MyPreferences
    {
        return new Board\MyPreferences($this->client);
    }

    /**
     * Board PowerUps API
     *
     * @return Board\PowerUps
     */
    public function powerUps(): PowerUps
    {
        return new Board\PowerUps($this->client);
    }
}
