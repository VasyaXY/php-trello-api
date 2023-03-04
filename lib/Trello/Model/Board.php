<?php

namespace Trello\Model;

use Trello\Exception\InvalidArgumentException;

/**
 * @codeCoverageIgnore
 */
class Board extends AbstractObject implements BoardInterface
{
    protected string $apiName = 'board';

    protected array $loadParams = [
        'fields' => 'all',
        'organization' => true,
        'organization_memberships' => 'all',
        'members' => 'all',
        'membersInvited' => 'all',
        'memberships' => 'all',
        'lists' => 'all',
    ];

    /**
     * {@inheritdoc}
     */
    public function setName(string $name): self
    {
        $this->data['name'] = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->data['name'];
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($desc): self
    {
        $this->data['desc'] = $desc;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): string
    {
        return $this->data['desc'];
    }

    /**
     * {@inheritdoc}
     */
    public function getDescriptionData(): string
    {
        return $this->data['descData'];
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl(): string
    {
        return $this->data['url'];
    }

    /**
     * {@inheritdoc}
     */
    public function getShortUrl(): string
    {
        return $this->data['shortUrl'];
    }

    /**
     * {@inheritdoc}
     */
    public function getShortLink(): string
    {
        return $this->data['shortLink'];
    }

    /**
     * {@inheritdoc}
     */
    public function setOrganizationId($organizationId): self
    {
        $this->data['idOrganization'] = $organizationId;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrganizationId(): string
    {
        return $this->data['idOrganization'];
    }

    /**
     * {@inheritdoc}
     */
    public function setOrganization(OrganizationInterface $organization): self
    {
        return $this->setOrganizationId($organization->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getOrganization(): Organization
    {
        return new Organization($this->client, $this->getOrganizationId());
    }

    /**
     * {@inheritdoc}
     */
    public function getLists(): array
    {
        $lists = [];

        foreach ($this->data['lists'] as $data) {
            $lists[$data['id']] = new CardList($this->client, $data['id']);
        }

        return $lists;
    }

    /**
     * {@inheritdoc}
     */
    public function getList($idOrName): CardListInterface|null
    {
        foreach ($this->getLists() as $list) {
            if ($list->getName() === $idOrName || $list->getId() === $idOrName) {
                return $list;
            }
        }

        throw new InvalidArgumentException(sprintf(
            'There is no list with name or id "%s" on this board ("%s")',
            $idOrName,
            $this->getName()
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setClosed($closed): self
    {
        $this->data['closed'] = $closed;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isClosed(): bool
    {
        return $this->data['closed'];
    }

    /**
     * {@inheritdoc}
     */
    public function setPinned($pinned): self
    {
        $this->data['pinned'] = $pinned;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isPinned(): bool
    {
        return $this->data['pinned'];
    }

    /**
     * {@inheritdoc}
     */
    public function setStarred($starred): self
    {
        $this->data['starred'] = $starred;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isStarred(): bool
    {
        return $this->data['starred'];
    }

    /**
     * {@inheritdoc}
     */
    public function setSubscribed($subscribed): self
    {
        $this->data['subscribed'] = $subscribed;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isSubscribed(): bool
    {
        return $this->data['subscribed'];
    }

    /**
     * {@inheritdoc}
     */
    public function isInvited(): bool
    {
        return $this->data['invited'];
    }

    /**
     * {@inheritdoc}
     */
    public function setRequiredRoleToInvite($role): self
    {
        $this->data['invitations'] = $role;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredRoleToInvite(): string
    {
        return $this->data['invitations'];
    }

    /**
     * {@inheritdoc}
     */
    public function setMemberships(array $memberships): self
    {
        $this->data['memberships'] = $memberships;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMemberships(): array
    {
        return $this->data['memberships'];
    }

    /**
     * {@inheritdoc}
     */
    public function setPreferences(array $prefs): self
    {
        $this->data['prefs'] = $prefs;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPreferences(): array
    {
        return $this->data['prefs'];
    }

    /**
     * {@inheritdoc}
     */
    public function setLabelNames(array $labelNames): self
    {
        $this->data['labelNames'] = $labelNames;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabelNames(): array
    {
        return $this->data['labelNames'];
    }

    /**
     * {@inheritdoc}
     */
    public function setPowerUps(array $powerUps): self
    {
        $this->data['powerUps'] = $powerUps;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPowerUps(): array
    {
        return $this->data['powerUps'];
    }

    /**
     * {@inheritdoc}
     */
    public function getDateOfLastActivity(): \DateTime
    {
        return new \DateTime($this->data['dateLastActivity']);
    }

    /**
     * {@inheritdoc}
     */
    public function getDateOfLastView(): \DateTime
    {
        return new \DateTime($this->data['dateLastView']);
    }
}
