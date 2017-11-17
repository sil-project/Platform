<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\SeedBatchBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Addressable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Descriptible;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Loggable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Searchable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Timestampable;
use Sil\Bundle\CRMBundle\Entity\OrganismInterface;

/**
 * Plot.
 */
class Plot
{
    use BaseEntity,
        Addressable,
        Timestampable,
        Loggable,
        Descriptible,
        Searchable;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var Collection
     */
    protected $seedBatches;

    /**
     * @var OrganismInterface
     */
    protected $producer;

    /**
     * @var Collection
     */
    protected $certifications;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->country = 'FR';
        $this->initCollections();
    }

    // implementation of __clone for duplication
    public function __clone()
    {
        $this->id = null;
        $this->code = null;
        $this->initCollections();
    }

    public function initCollections()
    {
        $this->seedBatches = new ArrayCollection();
        $this->certifications = new ArrayCollection();
    }

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return Plot
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
     * @param SeedBatchInterface $seedBatch
     *
     * @return Plot
     */
    public function addSeedBatch(SeedBatchInterface $seedBatch)
    {
        $this->seedBatches[] = $seedBatch;

        return $this;
    }

    /**
     * Remove seedBatch.
     *
     * @param SeedBatchInterface $seedBatch
     *
     * @return bool tRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeSeedBatch(SeedBatchInterface $seedBatch)
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

    /**
     * Set producer.
     *
     * @param OrganismInterface $producer
     *
     * @return Plot
     */
    public function setProducer(OrganismInterface $producer = null)
    {
        $this->producer = $producer;

        return $this;
    }

    /**
     * Set Organism (Producer in fact).
     *
     * @param OrganismInterface $producer
     *
     * @return Plot
     */
    public function setOrganism(OrganismInterface $producer = null)
    {
        $this->setProducer($producer);

        return $this;
    }

    /**
     * Get producer.
     *
     * @return OrganismInterface
     */
    public function getProducer()
    {
        return $this->producer;
    }

    /**
     * Add certifications.
     *
     * @param Certification $certifications
     *
     * @return Plot
     */
    public function addCertification(Certification $certifications)
    {
        $this->certifications[] = $certifications;

        return $this;
    }

    /**
     * Remove certification.
     *
     * @param Certification $certification
     */
    public function removeCertification(Certification $certification)
    {
        $this->certifications->removeElement($certification);
    }

    /**
     * Get certifications.
     *
     * @return Collection
     */
    public function getCertifications()
    {
        return $this->certifications;
    }
}
