<?php

namespace Trello\Api\Board;

use Trello\Api\AbstractApi;

/**
 * Trello Board Actions API
 * @link https://trello.com/docs/api/board
 *
 * Fully implemented.
 */
class Actions extends AbstractApi
{
    /**
     * Base path of board actions api
     * @var string
     */
    protected string $path = 'boards/#id#/actions';

    /**
     * Get actions related to a given board
     * @link https://trello.com/docs/api/board/#get-1-boards-board-id-actions
     *
     * @param string $id the board's id
     * @param array $params optional parameters
     *
     * @return array
     */
    public function all(string $id, array $params = []): array
    {
        return $this->get($this->getPath($id), $params);
    }
}
