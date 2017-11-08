<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Sil\Bundle\VarietyBundle\Entity\HasVarietyTrait;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\BaseEntity;

class ChannelDescription
{
    use BaseEntity,
        HasVarietyTrait;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $value;

    /**
     * Set value.
     *
     * @param string $value
     *
     * @return ChannelDescription
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @var Channel
     */
    protected $channel;

    public function __construct()
    {
    }

    /**
     * Add channel.
     *
     * @param Channel $channel
     */
    public function setChannel(Channel $channel)
    {
        $this->channel = $channel;
    }

    /**
     * Get channel.
     *
     * @return Channel
     */
    public function getChannel()
    {
        return $this->channel;
    }
}
