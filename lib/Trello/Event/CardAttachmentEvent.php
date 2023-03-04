<?php

namespace Trello\Event;

class CardAttachmentEvent extends CardEvent
{
    /**
     * @var array
     */
    protected array $attachment;

    /**
     * Set attachment
     *
     * @param array $attachment
     */
    public function setAttachment(array $attachment): void
    {
        $this->attachment = $attachment;
    }

    /**
     * Get attachment
     *
     * @return array
     */
    public function getAttachment(): array
    {
        return $this->attachment;
    }
}
