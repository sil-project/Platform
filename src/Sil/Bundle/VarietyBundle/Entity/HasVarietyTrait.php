<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\VarietyBundle\Entity;

trait HasVarietyTrait
{
    /**
     * @var Variety
     */
    protected $variety;

    /**
     * Get variety.
     *
     * @return Variety
     */
    public function getVariety()
    {
        return $this->variety;
    }

    /**
     * Set variety.
     *
     * @param Variety $variety
     *
     * @return self
     */
    public function setVariety($variety)
    {
        $this->variety = $variety;

        return $this;
    }
}
