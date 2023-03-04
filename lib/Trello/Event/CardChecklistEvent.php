<?php

namespace Trello\Event;

use Trello\Model\CheckListInterface;

class CardChecklistEvent extends CardEvent
{
    /**
     * @var CheckListInterface
     */
    protected CheckListInterface $checklist;

    /**
     * Set checklist
     *
     * @param CheckListInterface $checklist
     */
    public function setChecklist(CheckListInterface $checklist): void
    {
        $this->checklist = $checklist;
    }

    /**
     * Get checklist
     *
     * @return CheckListInterface
     */
    public function getChecklist(): CheckListInterface
    {
        return $this->checklist;
    }
}
