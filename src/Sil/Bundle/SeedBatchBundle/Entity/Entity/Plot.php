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

use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\OuterExtension\LibrinfoSeedBatchBundle\PlotExtension;
use Blast\BaseEntitiesBundle\Entity\Traits\Addressable;
use Blast\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\BaseEntitiesBundle\Entity\Traits\Descriptible;
use Blast\BaseEntitiesBundle\Entity\Traits\Loggable;
use Blast\BaseEntitiesBundle\Entity\Traits\Searchable;
use Blast\BaseEntitiesBundle\Entity\Traits\Timestampable;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;

/**
 * Plot.
 */
class Plot
{
    use BaseEntity,
        PlotExtension,
        OuterExtensible,
        Addressable,
        Timestampable,
        Loggable,
        Descriptible,
        Searchable;

    /**
     * @var string
     */
    private $code;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $seedBatches;

    /**
     * @var \Librinfo\CRMBundle\Entity\Organism
     */
    private $producer;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $certifications;

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
     * @param \Librinfo\SeedBatchBundle\Entity\SeedBatch $seedBatch
     *
     * @return Plot
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

    /**
     * Set producer.
     *
     * @param \Librinfo\CRMBundle\Entity\Organism $producer
     *
     * @return Plot
     */
    public function setProducer(\Librinfo\CRMBundle\Entity\Organism $producer = null)
    {
        $this->producer = $producer;

        return $this;
    }

    /**
     * Set Organism (Producer in fact).
     *
     * @param \Librinfo\CRMBundle\Entity\Organism $producer
     *
     * @return Plot
     */
    public function setOrganism(\Librinfo\CRMBundle\Entity\Organism $producer = null)
    {
        $this->setProducer($producer);

        return $this;
    }

    /**
     * Get producer.
     *
     * @return \Librinfo\CRMBundle\Entity\Organism
     */
    public function getProducer()
    {
        return $this->producer;
    }

    /**
     * Add certifications.
     *
     * @param \Librinfo\SeedBatchBundle\Entity\Certification $certifications
     *
     * @return Plot
     */
    public function addCertification(\Librinfo\SeedBatchBundle\Entity\Certification $certifications)
    {
        $this->certifications[] = $certifications;

        return $this;
    }

    /**
     * Remove certification.
     *
     * @param \Librinfo\SeedBatchBundle\Entity\Certification $certification
     */
    public function removeCertification(\Librinfo\SeedBatchBundle\Entity\Certification $certification)
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
