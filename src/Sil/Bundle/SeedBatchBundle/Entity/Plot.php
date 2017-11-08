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

use Doctrine\Common\Collections\ArrayCollection;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Addressable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Descriptible;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Loggable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Searchable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Timestampable;

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
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $seedBatches;

    /**
     * @var \Sil\Bundle\CRMBundle\Entity\Organism
     */
    protected $producer;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $certifications;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->country = 'FR';
        $this->initCollections();
        $this->initOuterExtendedClasses();
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
     * @param \Sil\Bundle\SeedBatchBundle\Entity\SeedBatch $seedBatch
     *
     * @return Plot
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

    /**
     * Set producer.
     *
     * @param \Sil\Bundle\CRMBundle\Entity\Organism $producer
     *
     * @return Plot
     */
    public function setProducer(\Sil\Bundle\CRMBundle\Entity\Organism $producer = null)
    {
        $this->producer = $producer;

        return $this;
    }

    /**
     * Set Organism (Producer in fact).
     *
     * @param \Sil\Bundle\CRMBundle\Entity\Organism $producer
     *
     * @return Plot
     */
    public function setOrganism(\Sil\Bundle\CRMBundle\Entity\Organism $producer = null)
    {
        $this->setProducer($producer);

        return $this;
    }

    /**
     * Get producer.
     *
     * @return \Sil\Bundle\CRMBundle\Entity\Organism
     */
    public function getProducer()
    {
        return $this->producer;
    }

    /**
     * Add certifications.
     *
     * @param \Sil\Bundle\SeedBatchBundle\Entity\Certification $certifications
     *
     * @return Plot
     */
    public function addCertification(\Sil\Bundle\SeedBatchBundle\Entity\Certification $certifications)
    {
        $this->certifications[] = $certifications;

        return $this;
    }

    /**
     * Remove certification.
     *
     * @param \Sil\Bundle\SeedBatchBundle\Entity\Certification $certification
     */
    public function removeCertification(\Sil\Bundle\SeedBatchBundle\Entity\Certification $certification)
    {
        $this->certifications->removeElement($certification);
    }

    /**
     * Get certifications.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCertifications()
    {
        return $this->certifications;
    }
}
