<?php

namespace Trello\Model;

use Trello\Events;
use Trello\Exception\InvalidArgumentException;
use Trello\Exception\RuntimeException;

/**
 * @codeCoverageIgnore
 */
class Label extends AbstractObject implements CardInterface
{
    protected $apiName = 'label';

    protected $loadParams = [
        'fields' => 'all',
        'board' => true,
    ];

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->data['name'] = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->data['name'];
    }

    /**
     * {@inheritdoc}
     */
    public function setColor($color)
    {
        $this->data['color'] = $color;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getColor($color)
    {
        return $this->data['color'];
    }
    
}
