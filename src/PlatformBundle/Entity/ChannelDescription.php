<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PlatformBundle\Entity;

use Sil\Bundle\VarietyBundle\Entity\Association\HasVarietyTrait;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Sylius\Component\Core\Model\ChannelInterface;

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
     * @var ChannelInterface
     */
    protected $channel;

    public function __construct()
    {
    }

    /**
     * Add channel.
     *
     * @param ChannelInterface $channel
     */
    public function setChannel(ChannelInterface $channel)
    {
        $this->channel = $channel;
    }

    /**
     * Get channel.
     *
     * @return ChannelInterface
     */
    public function getChannel()
    {
        return $this->channel;
    }
}
