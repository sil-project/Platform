<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Entity\Traits;

trait Sortable
{
    /**
     * @var string
     */
    protected $sortRank;

    /**
     * @param string $sortRank
     *
     * @return Sortable
     */
    public function setSortRank($sortRank)
    {
        $this->sortRank = $sortRank;

        return $this;
    }

    /**
     * @return string
     */
    public function getSortRank()
    {
        return $this->sortRank;
    }
}
