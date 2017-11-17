<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\VarietyBundle\Entity\Association;

use Sil\Bundle\VarietyBundle\Entity\VarietyInterface;

trait HasVarietyTrait
{
    /**
     * @var Variety
     */
    protected $variety;

    /**
     * Get variety.
     *
     * @return VarietyInterface
     */
    public function getVariety(): ?VarietyInterface
    {
        return $this->variety;
    }

    /**
     * Set variety.
     *
     * @param VarietyInterface $variety
     *
     * @return self
     */
    public function setVariety(VarietyInterface $variety)
    {
        $this->variety = $variety;

        return $this;
    }
}
