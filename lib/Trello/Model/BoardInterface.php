<?php

namespace Trello\Model;

interface BoardInterface extends ObjectInterface
{
    /**
     * Set name
     *
     * @param string $name a string with a length from 1 to 16384
     *
     * @return BoardInterface
     */
    public function setName(string $name): self;

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set description
     *
     * @param string $desc a string with a length from 0 to 16384
     *
     * @return BoardInterface
     */
    public function setDescription(string $desc): self;

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Get description data
     *
     * @return string
     */
    public function getDescriptionData(): string;

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * Get short url
     *
     * @return string
     */
    public function getShortUrl(): string;

    /**
     * Get short link
     *
     * @return string
     */
    public function getShortLink(): string;

    /**
     * Set organization id
     *
     * @param string $organizationId the organization'is id, ie. a string with a length from 0 to 16384
     *
     * @return BoardInterface
     */
    public function setOrganizationId(string $organizationId): self;

    /**
     * Get organization id
     *
     * @return string
     */
    public function getOrganizationId(): string;

    /**
     * Set organization
     *
     * @param OrganizationInterface $organization
     *
     * @return BoardInterface
     */
    public function setOrganization(OrganizationInterface $organization): self;

    /**
     * Get organization
     *
     * @return OrganizationInterface
     */
    public function getOrganization(): self;

    /**
     * Get lists
     *
     * @return CardListInterface[]
     */
    public function getLists(): array;

    /**
     * Set closed
     *
     * @param bool $closed
     *
     * @return BoardInterface
     */
    public function setClosed($closed): self;

    /**
     * Get closed
     *
     * @return bool
     */
    public function isClosed(): bool;

    /**
     * Set pinned
     *
     * @param bool $pinned
     *
     * @return BoardInterface
     */
    public function setPinned($pinned): self;

    /**
     * Get pinned
     *
     * @return bool
     */
    public function isPinned(): bool;

    /**
     * Set starred
     *
     * @param bool $starred
     *
     * @return BoardInterface
     */
    public function setStarred($starred): self;

    /**
     * Get starred
     *
     * @return bool
     */
    public function isStarred(): bool;

    /**
     * Set subscribed
     *
     * @param bool $subscribed
     *
     * @return BoardInterface
     */
    public function setSubscribed($subscribed): self;

    /**
     * Get subscribed
     *
     * @return bool
     */
    public function isSubscribed(): bool;

    /**
     * Get invited
     *
     * @return bool
     */
    public function isInvited(): bool;

    /**
     * Set the role required to invite
     *
     * @param string $role one of 'members', 'admins'
     *
     * @return BoardInterface
     */
    public function setRequiredRoleToInvite($role): self;

    /**
     * Get the role required to invite
     *
     * @return string
     */
    public function getRequiredRoleToInvite(): string;

    /**
     * Set memberships
     *
     * @param array $memberships an array of arrays containing:
     *                           - idMembership: a member id
     *                           - type: one of 'normal', 'observer', 'admin'
     *                           - member_fields (optional)
     *
     * @return BoardInterface
     */
    public function setMemberships(array $memberships): self;

    /**
     * Get memberships
     *
     * @return array
     */
    public function getMemberships(): array;

    /**
     * Set prefs
     *
     * @param array $prefs a preferences array that may contain the following keys:
     *                     - permissionLevel: 'private', 'org', 'public'
     *                     - selfJoin: 'true', 'false'
     *                     - cardCovers: 'true', 'false'
     *                     - invitations: 'members', 'admins'
     *                     - voting: 'members', 'org', 'public', 'disabled', 'observers'
     *                     - comments: 'members', 'org', 'public', 'disabled', 'observers'
     *                     - background: a standard background name, or the id of a custom background
     *                     - cardAging: 'regular', 'pirate'
     *                     - calendarFeedEnabled: 'true', 'false'
     *
     * @return BoardInterface
     */
    public function setPreferences(array $prefs): self;

    /**
     * Get prefs
     *
     * @return array
     */
    public function getPreferences(): array;

    /**
     * Set label names
     *
     * @param array $labelNames an array of 'color' => 'label name'
     *                          existing colors are: 'green', 'yellow', 'orange', 'red', 'purple', 'blue'
     *
     * @return BoardInterface
     */
    public function setLabelNames(array $labelNames): self;

    /**
     * Get label names
     *
     * @return array
     */
    public function getLabelNames(): array;

    /**
     * Set power ups
     *
     * @param array $powerUps an array of 'voting', 'cardAging', 'calendar', 'recap'
     *
     * @return BoardInterface
     */
    public function setPowerUps(array $powerUps): self;

    /**
     * Get power ups
     *
     * @return array
     */
    public function getPowerUps(): array;

    /**
     * Get date last activity
     *
     * @return \DateTime
     */
    public function getDateOfLastActivity(): \DateTime;

    /**
     * Get date of last view
     *
     * @return \DateTime
     */
    public function getDateOfLastView(): \DateTime;
}
