<?php

namespace Trello\Api\Organization;

use Trello\Api\Board\Members as BoardMembers;

/**
 * Trello Organization Members API
 * @link https://developer.atlassian.com/cloud/trello/rest/api-group-organizations/
 *
 * Fully implemented.
 */
class Members extends BoardMembers
{
    /**
     * Base path of organization members api
     * @var string
     */
    protected string $path = 'organizations/#id#/members';
}
