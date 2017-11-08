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

namespace Sil\Bundle\SeedBatchBundle\Entity;

use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Addressable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Descriptible;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Loggable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * SeedFarm.
 */
class SeedFarm
{
    use BaseEntity,
        Addressable,
        Timestampable,
        Loggable,
        Descriptible;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $seedBatches;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->country = 'FR';
        $this->seedBatches = new ArrayCollection();
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
     * @param \Sil\Bundle\SeedBatchBundle\Entity\SeedBatch $seedBatch
     *
     * @return SeedFarm
     */
    public function addSeedBatch(\Sil\Bundle\SeedBatchBundle\Entity\SeedBatch $seedBatch)
    {
        $this->seedBatches[] = $seedBatch;

        return $this;
    }

    /**
     * Remove seedBatch.
     *
     * @param \Sil\Bundle\SeedBatchBundle\Entity\SeedBatch $seedBatch
     *
     * @return bool tRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeSeedBatch(\Sil\Bundle\SeedBatchBundle\Entity\SeedBatch $seedBatch)
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
