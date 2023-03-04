<?php

namespace Trello\Event;

use Trello\Model\MemberInterface;

class CardMemberEvent extends CardEvent
{
    /**
     * @var MemberInterface
     */
    protected MemberInterface $member;

    /**
     * Set member
     *
     * @param MemberInterface $member
     */
    public function setMember(MemberInterface $member): void
    {
        $this->member = $member;
    }

    /**
     * Get member
     *
     * @return MemberInterface
     */
    public function getMember(): MemberInterface
    {
        return $this->member;
    }
}
