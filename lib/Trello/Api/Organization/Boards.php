<?php

namespace Trello\Api\Organization;

use Trello\Api\Member\Boards as MemberBoards;

/**
 * Trello Organization Boards API
 * @link https://developer.atlassian.com/cloud/trello/rest/api-group-organizations/
 *
 * Fully implemented.
 */
class Boards extends MemberBoards
{
    /**
     * Base path of organization boards api
     * @var string
     */
    protected string $path = 'organizations/#id#/boards';
}
