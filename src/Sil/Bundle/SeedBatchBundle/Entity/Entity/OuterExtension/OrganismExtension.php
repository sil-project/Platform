<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\SeedBatchBundle\Entity\OuterExtension;

trait OrganismExtension
{
    use HasSeedBatches;
    use HasPlots;

    /**
     * @var string
     */
    private $seedProducerCode;

    /**
     * @var bool
     */
    private $seedProducer = false;

    /**
     * Set seedProducerCode.
     *
     * @param string $seedProducerCode
     *
     * @return Organism
     */
    public function setSeedProducerCode($seedProducerCode)
    {
        $this->seedProducerCode = $seedProducerCode;

        return $this;
    }

    /**
     * Get seedProducerCode.
     *
     * @return string
     */
    public function getSeedProducerCode()
    {
        return $this->seedProducerCode;
    }

    public function producerToString()
    {
        return (string) $this;
    }

    public function isSeedProducer()
    {
        return (bool) $this->seedProducer;
    }

    public function setSeedProducer($seedProducer)
    {
        $this->seedProducer = $seedProducer;

        return $this;
    }
}
