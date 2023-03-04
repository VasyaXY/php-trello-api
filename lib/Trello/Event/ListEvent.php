<?php

namespace Trello\Event;

use Trello\Model\CardListInterface;

class ListEvent extends AbstractEvent
{
    /**
     * @var CardListInterface
     */
    protected CardListInterface $list;

    /**
     * Set cardlist
     *
     * @param CardListInterface $list
     */
    public function setList(CardListInterface $list): void
    {
        $this->list = $list;
    }

    /**
     * Get cardlist
     *
     * @return CardListInterface
     */
    public function getList(): CardListInterface
    {
        return $this->list;
    }
}
