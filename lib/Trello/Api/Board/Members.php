<?php

namespace Trello\Api\Board;

use Trello\Api\AbstractApi;
use Trello\Api\Member;
use Trello\Exception\InvalidArgumentException;

/**
 * Trello Board Members API
 * @link https://trello.com/docs/api/board
 *
 * Fully implemented.
 */
class Members extends AbstractApi
{
    /**
     * Base path of board members api
     * @var string
     */
    protected string $path = 'boards/#id#/members';

    /**
     * Get a given board's members
     * @link https://trello.com/docs/api/board/#get-1-boards-board-id-members
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

    /**
     * Remove a given member from a given board
     * @link https://trello.com/docs/api/board/#get-1-boards-board-id-members
     *
     * @param string $id the board's id
     * @param string $memberId the member's id
     *
     * @return array
     */
    public function remove(string $id, string $memberId): array
    {
        return $this->delete($this->getPath($id) . '/' . $memberId);
    }

    /**
     * Filter members related to a given board
     * @link https://trello.com/docs/api/board/#get-1-boards-board-id-members-filter
     *
     * @param string $id the board's id
     * @param string|array $filter array of / one of 'none', 'normal', 'admins', 'owners', 'all'
     *
     * @return array
     */
    public function filter(string $id, string|array $filter = 'all'): array
    {
        $allowed = ['none', 'normal', 'admins', 'owners', 'all'];
        $filters = $this->validateAllowedParameters($allowed, $filter, 'filter');

        return $this->get($this->getPath($id) . '/' . implode(',', $filters));
    }

    /**
     * Get a member's cards related to a given board
     * @link https://trello.com/docs/api/board/#get-1-boards-board-id-members-filter
     *
     * @param string $id the board's id
     * @param string $memberId the member's id
     * @param array $params optional parameters
     *
     * @return array
     */
    public function cards(string $id, string $memberId, array $params = []): array
    {
        return $this->get($this->getPath($id) . '/' . rawurlencode($memberId) . '/cards', $params);
    }

    /**
     * Add member to a given board
     * @link https://trello.com/docs/api/board/#put-1-boards-board-id-members
     *
     * @param string $id the board's id
     * @param string $email the member's email address
     * @param string $fullName the member's full name
     * @param string $role one of 'normal', 'observer' or 'admin'
     *
     * @return array
     */
    public function invite(string $id, string $email, string $fullName, string $role = 'normal'): array
    {
        $roles = ['normal', 'observer', 'admin'];

        if (!in_array($role, $roles)) {
            throw new InvalidArgumentException(sprintf(
                'The "role" parameter must be one of "%s".',
                implode(", ", $roles)
            ));
        }

        $params = [
            'email' => $email,
            'fullName' => $fullName,
            'type' => $role,
        ];

        return $this->put($this->getPath($id), $params);
    }

    /**
     * Add member to a given board
     * @link https://trello.com/docs/api/board/#put-1-boards-board-id-members
     *
     * @param string $idBoard the board's id
     * @param string $idMember the member's id
     * @param string $role one of 'normal', 'observer' or 'admin'
     *
     * @return array
     */
    public function addMember(string $idBoard, string $idMember, string $inviteText = '', string $role = 'normal'): array
    {
        $roles = ['normal', 'observer', 'admin'];

        if (!in_array($role, $roles)) {
            throw new InvalidArgumentException(sprintf(
                'The "role" parameter must be one of "%s".',
                implode(", ", $roles)
            ));
        }

        $params = [
            'type' => $role,
            'invitationMessage' => $inviteText
        ];

        return $this->put($this->getPath($idBoard) . '/' . rawurlencode($idMember) . '/', $params);
    }

    /**
     * Get members invited to a given board
     * @link https://trello.com/docs/api/board/#get-1-boards-board-id-membersinvited
     *
     * @param string $id the board's id
     * @param array $params optional parameters
     *
     * @return array
     */
    public function getInvitedMembers(string $id, array $params = []): array
    {
        return $this->get($this->getPath($id) . 'Invited', $params);
    }

    /**
     * Get a field related to a member invited to a given board
     * @link https://trello.com/docs/api/board/#get-1-boards-board-id-membersinvited-field
     *
     * @param string $id the board's id
     * @param string $field the member's field name
     *
     * @return array
     */
    public function getInvitedMembersField(string $id, string $field): array
    {
        $this->validateAllowedParameters(Member::$fields, $field, 'field');

        return $this->get($this->getPath($id) . 'Invited/' . rawurlencode($field));
    }

    /**
     * Set the role of a user or an organization on a given board
     * @link https://trello.com/docs/api/board/index.html#put-1-boards-board-id-members-idmember
     *
     * @param string $id the board's id
     * @param string $memberOrOrganization the member's id, user name or an organization name
     *
     * @return array
     */
    public function setRole(string $id, string $memberOrOrganization, string $role): array
    {
        $roles = ['normal', 'observer', 'admin'];

        if (!in_array($role, $roles)) {
            throw new InvalidArgumentException(sprintf(
                'The "role" parameter must be one of "%s".',
                implode(", ", $roles)
            ));
        }

        $params = [
            'idMember' => $memberOrOrganization,
            'type' => $role,
        ];

        return $this->post($this->getPath($id) . '/' . rawurlencode($memberOrOrganization), $params);
    }
}
