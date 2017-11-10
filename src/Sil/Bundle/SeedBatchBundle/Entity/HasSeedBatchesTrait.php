<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\SeedBatchBundle\Entity;

use Doctrine\Common\Collections\Collection;

trait HasSeedBatchesTrait
{
    /**
     * @var Collection
     */
    protected $seedBatches;

    /**
     * Add seed batch.
     *
     * @param SeedBatch $seedBatch
     *
     * @return self
     */
    public function addSeedBatch($seedBatch)
    {
        $this->seedBatches->add($seedBatch);

        if (method_exists(get_class($this), 'setProducer')) {
            $seedBatch->setProducer($this);
        }

        return $this;
    }

    /**
     * Remove seed batch.
     *
     * @param SeedBatch $seedBatch
     *
     * @return bool tRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeSeedBatch($seedBatch)
    {
        return $this->seedBatches->removeElement($seedBatch);
    }

    /**
     * Get seedBatches.
     *
     * @return Collection
     */
    public function getSeedBatches()
    {
        return $this->seedBatches;
    }
}
