<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\SeedBatchBundle\Entity;

use AppBundle\Entity\OuterExtension\LibrinfoSeedBatchBundle\SeedFarmExtension;
use Blast\BaseEntitiesBundle\Entity\Traits\Addressable;
use Blast\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\BaseEntitiesBundle\Entity\Traits\Descriptible;
use Blast\BaseEntitiesBundle\Entity\Traits\Loggable;
use Blast\BaseEntitiesBundle\Entity\Traits\Timestampable;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;

/**
 * SeedFarm.
 */
class SeedFarm
{
    use BaseEntity,
        SeedFarmExtension,
        OuterExtensible,
        Addressable,
        Timestampable,
        Loggable,
        Descriptible;

    /**
     * @var string
     */
    private $code;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $seedBatches;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->country = 'FR';
        $this->seedBatches = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return SeedFarm
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Add seedBatch.
     *
     * @param \Librinfo\SeedBatchBundle\Entity\SeedBatch $seedBatch
     *
     * @return SeedFarm
     */
    public function addSeedBatch(\Librinfo\SeedBatchBundle\Entity\SeedBatch $seedBatch)
    {
        $this->seedBatches[] = $seedBatch;

        return $this;
    }

    /**
     * Remove seedBatch.
     *
     * @param \Librinfo\SeedBatchBundle\Entity\SeedBatch $seedBatch
     *
     * @return bool tRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeSeedBatch(\Librinfo\SeedBatchBundle\Entity\SeedBatch $seedBatch)
    {
        return $this->seedBatches->removeElement($seedBatch);
    }

    /**
     * Get seedBatches.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeedBatches()
    {
        return $this->seedBatches;
    }
}
