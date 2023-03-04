<?php

namespace Trello\Event;

use Trello\Model\CommentInterface;

class CardCommentEvent extends CardEvent
{
    /**
     * @var CommentInterface
     */
    protected CommentInterface $comment;

    /**
     * Set comment
     *
     * @param CommentInterface $comment
     */
    public function setComment(CommentInterface $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return CommentInterface
     */
    public function getComment(): CommentInterface
    {
        return $this->comment;
    }
}
