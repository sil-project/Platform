<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\DashboardBundle\Block;

class BlockRegistry
{
    /**
     * @var array
     */
    private $blocks;

    public function __construct()
    {
        $this->blocks = [];
    }

    public function registerBlock($block)
    {
        $this->blocks[] = $block;
    }

    public function getRegistredBlocks()
    {
        return $this->blocks;
    }
}
