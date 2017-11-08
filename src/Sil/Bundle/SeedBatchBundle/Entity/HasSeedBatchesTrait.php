<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\SeedBatchBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Sil\Bundle\SeedBatchBundle\Entity\SeedBatch;

trait HasSeedBatchesTrait
{
    /**
     * @var Collection
     */
    private $seedBatches;

    /**
     * Add seed batch.
     *
     * @param SeedBatch $seedBatch
     *
     * @return self
     */
    public function addSeedBatch($seedBatch)
    {
        $this->seedBatches[] = $seedBatch;

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
