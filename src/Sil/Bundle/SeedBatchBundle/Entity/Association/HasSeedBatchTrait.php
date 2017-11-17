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

use Sil\Bundle\SeedBatchBundle\Entity\SeedBatchInterface;

trait HasSeedBatchTrait
{
    /**
     * @var SeedBatchInterface
     */
    protected $seedBatch;

    /**
     * Get seed batch.
     *
     * @return SeedBatchInterface
     */
    public function getSeedBatch(): SeedBatchInterface
    {
        return $this->seedBatch;
    }

    /**
     * Set seed batch.
     *
     * @param SeedBatchInterface $seedBatch
     *
     * @return self
     */
    public function setSeedBatch(SeedBatchInterface $seedBatch)
    {
        $this->seedBatch = $seedBatch;

        return $this;
    }
}
