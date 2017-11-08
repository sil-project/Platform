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

namespace Sil\Bundle\VarietyBundle\Entity;

/**
 * VarietyDescription.
 */
class VarietyDescription extends GenericDescription
{
    const DESCRIPTION_AMATEUR = 'amateur';
    const DESCRIPTION_COMMERCIAL = 'commercial';
    const DESCRIPTION_CULTURE = 'culture';
    const DESCRIPTION_PLANT = 'plant';
    const DESCRIPTION_PRODUCTION = 'production';
    const DESCRIPTION_PROFESSIONAL = 'professional';

    /**
     * @var \Sil\Bundle\VarietyBundle\Entity\Variety
     */
    protected $variety;

    /**
     * Set variety.
     *
     * @param \Sil\Bundle\VarietyBundle\Entity\Variety $variety
     *
     * @return VarietyDescription
     */
    public function setVariety($variety = null)
    {
        $this->variety = $variety;

        return $this;
    }

    /**
     * Get variety.
     *
     * @return \Sil\Bundle\VarietyBundle\Entity\Variety
     */
    public function getVariety()
    {
        return $this->variety;
    }

    public function __clone()
    {
        $this->id = null;
        $this->setVariety(null);
    }
}
