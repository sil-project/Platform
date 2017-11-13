<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\SeedBatchBundle\Entity\Association;

trait HasSeedBatchTrait
{
    /**
     * @var SeedBatch
     */
    protected $seedBatch;

    /**
     * Get seed batch.
     *
     * @return SeedBatch
     */
    public function getSeedBatch()
    {
        return $this->seedBatch;
    }

    /**
     * Set seed batch.
     *
     * @param SeedBatch $seedBatch
     *
     * @return self
     */
    public function setSeedBatch($seedBatch)
    {
        $this->seedBatch = $seedBatch;

        return $this;
    }
}
