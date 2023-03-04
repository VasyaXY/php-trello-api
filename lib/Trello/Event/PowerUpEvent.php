<?php

namespace Trello\Event;

use Trello\Model\PowerUpInterface;

class PowerUpEvent extends AbstractEvent
{
    /**
     * @var PowerUpInterface
     */
    protected PowerUpInterface $powerUp;

    /**
     * Set powerUp
     *
     * @param PowerUpInterface $powerUp
     */
    public function setPowerUp(PowerUpInterface $powerUp): void
    {
        $this->powerUp = $powerUp;
    }

    /**
     * Get powerUp
     *
     * @return PowerUpInterface
     */
    public function getPowerUp(): PowerUpInterface
    {
        return $this->powerUp;
    }
}
