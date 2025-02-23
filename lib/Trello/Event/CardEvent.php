<?php

namespace Trello\Event;

use Trello\Model\CardInterface;

class CardEvent extends AbstractEvent
{
    /**
     * @var CardInterface
     */
    protected CardInterface $card;

    /**
     * Set card
     *
     * @param CardInterface $card
     */
    public function setCard(CardInterface $card): CardInterface
    {
        $this->card = $card;
    }

    /**
     * Get card
     *
     * @return CardInterface
     */
    public function getCard(): CardInterface
    {
        return $this->card;
    }
}
