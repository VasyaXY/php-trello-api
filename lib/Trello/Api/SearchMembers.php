<?php

namespace Trello\Api;

use Trello\Api\Member\Cards;
use Trello\Api\Member\Notifications;
use Trello\Api\Member\Webhooks;

/**
 * Trello Member API
 * @link https://developer.atlassian.com/cloud/trello/rest/api-group-search/
 */
class SearchMembers extends AbstractApi
{
    /**
     * Base path of members api
     * @var string
     */
    protected string $path = 'search/members';

    /**
     * Member fields
     * @link https://developer.atlassian.com/cloud/trello/rest/api-group-search/
     * @var array
     */
    public static array $fields = [
        "id",
        "activityBlocked",
        "avatarHash",
        "avatarUrl",
        "bio",
        "bioData",
        "confirmed",
        "fullName",
        "idEnterprise",
        "idEnterprisesDeactivated",
        "idMemberReferrer",
        "idPremOrgsAdmin",
        "initials",
        "memberType",
        "nonPublic",
        "nonPublicAvailable",
        "products",
        "url",
        "username",
        "status",
        "aaEmail",
        "aaEnrolledDate",
        "aaId",
        "avatarSource",
        "email",
        "gravatarHash",
        "idBoards",
        "idOrganizations",
        "idEnterprisesAdmin",
        "limits",
        "loginTypes",
        "marketingOptIn",
        "messagesDismissed",
        "oneTimeMessagesDismissed",
        "prefs",
        "trophies",
        "uploadedAvatarHash",
        "uploadedAvatarUrl",
        "premiumFeatures",
        "isAaMastered",
        "ixUpdate",
        "idBoardsPinned"
    ];

    /**
     * Find a member by id or username
     * @link https://trello.com/docs/api/member/index.html#get-1-members-idmember-or-username
     *
     * @param string $query search string
     * @param array $params optional attributes
     *
     * @return array list info
     */
    public function search(string $query, array $params = []): array
    {
        $params['query'] = $query;
        $params['limit'] = 20;
        return $this->get($this->getPath() . '/', $params);
    }
}
