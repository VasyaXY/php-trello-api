<?php

namespace Trello\Event;

use Trello\Model\OrganizationInterface;

class OrganizationEvent extends AbstractEvent
{
    /**
     * @var OrganizationInterface
     */
    protected OrganizationInterface $organization;

    /**
     * Set organization
     *
     * @param OrganizationInterface $organization
     */
    public function setOrganization(OrganizationInterface $organization): void
    {
        $this->organization = $organization;
    }

    /**
     * Get organization
     *
     * @return OrganizationInterface
     */
    public function getOrganization(): OrganizationInterface
    {
        return $this->organization;
    }
}
