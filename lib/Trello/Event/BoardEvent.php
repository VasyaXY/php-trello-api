<?php

namespace Trello\Event;

use Trello\Model\BoardInterface;

class BoardEvent extends AbstractEvent
{
    /**
     * @var BoardInterface
     */
    protected BoardInterface $board;

    /**
     * Set board
     *
     * @param BoardInterface $board
     */
    public function setBoard(BoardInterface $board): void
    {
        $this->board = $board;
    }

    /**
     * Get board
     *
     * @return BoardInterface
     */
    public function getBoard(): BoardInterface
    {
        return $this->board;
    }
}
