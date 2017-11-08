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

/**
 * 
 */
class ChannelDescription
{

    use HasVarietyTrait;

    /**
     * @var Channel
     */
    protected $channel;

    function __construct()
    {
        
    }

    /**
     * Add channel.
     *
     * @param Channel $channel
     *
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
