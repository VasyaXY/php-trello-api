<?php

namespace Trello\Api\Member;

use Trello\Api\AbstractApi;
use Trello\Api\Board;
use Trello\Api\Member\Board\Backgrounds;
use Trello\Api\Member\Board\Stars;

/**
 * Trello Member Boards API
 * @link https://trello.com/docs/api/member
 *
 * Fully implemented.
 */
class Webhooks extends AbstractApi
{
    protected string $path = 'members/#id#/tokens';

    /**
     * Get boads related to a given member
     * @link https://trello.com/docs/api/member/#get-1-members-idmember-or-username-boards
     *
     * @param string $id the member's id or username
     * @param array $params optional parameters
     *
     * @return array
     */
    public function all(string $id = "me"): array
    {
        return $this->get($this->getPath($id), ['webhooks' => true]);
    }
}
