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

use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Descriptible;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Loggable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Searchable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Timestampable;

/**
 * SeedBatch.
 */
class SeedBatch
{
    use BaseEntity,
        Timestampable,
        Loggable,
        Descriptible,
        Searchable;

    /**
     * @var \Sil\Bundle\VarietyBundle\Entity\Variety
     */
    protected $variety;

    /**
     * @var \Sil\Bundle\CRMBundle\Entity\Organism
     */
    protected $producer;

    /**
     * @var \Sil\Bundle\SeedBatchBundle\Entity\Plot
     */
    protected $plot;

    /**
     * @var \Sil\Bundle\SeedBatchBundle\Entity\SeedFarm
     */
    protected $seedFarm;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var int
     */
    protected $batchNumber;

    /**
     * @var int
     */
    protected $productionYear;

    /**
     * @var float
     */
    protected $germinationRate;

    /**
     * @var \DateTime
     */
    protected $germinationDate;

    /**
     * @var float
     */
    protected $tkw;

    /**
     * @var \DateTime
     */
    protected $tkwDate;

    /**
     * @var string
     */
    protected $certifications;

    /**
     * @var int
     */
    protected $grossProducerWeight;

    /**
     * @var int
     */
    protected $grossDeliveredWeight;

    /**
     * @var int
     */
    protected $netScreenedWeight;

    /**
     * @var int
     */
    protected $toScreenWeight;

    /**
     * @var \DateTime
     */
    protected $deliveryDate;

    /**
     * @var bool
     */
    protected $deliveryNote;

    public function __toString()
    {
        return (string) ($this->code ? $this->code : $this->id);
    }

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return SeedBatch
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
     * Set batchNumber.
     *
     * @param int $batchNumber
     *
     * @return SeedBatch
     */
    public function setBatchNumber($batchNumber)
    {
        $this->batchNumber = $batchNumber;

        return $this;
    }

    /**
     * Get batchNumber.
     *
     * @return int
     */
    public function getBatchNumber()
    {
        return $this->batchNumber;
    }

    /**
     * Set productionYear.
     *
     * @param int $productionYear
     *
     * @return SeedBatch
     */
    public function setProductionYear($productionYear)
    {
        $this->productionYear = $productionYear;

        return $this;
    }

    /**
     * Get productionYear.
     *
     * @return int
     */
    public function getProductionYear()
    {
        return $this->productionYear;
    }

    /**
     * Set germinationRate.
     *
     * @param float $germinationRate
     *
     * @return SeedBatch
     */
    public function setGerminationRate($germinationRate)
    {
        $this->germinationRate = $germinationRate;

        return $this;
    }

    /**
     * Get germinationRate.
     *
     * @return float
     */
    public function getGerminationRate()
    {
        return $this->germinationRate;
    }

    /**
     * Set germinationDate.
     *
     * @param \DateTime $germinationDate
     *
     * @return SeedBatch
     */
    public function setGerminationDate($germinationDate)
    {
        $this->germinationDate = $germinationDate;

        return $this;
    }

    /**
     * Get germinationDate.
     *
     * @return \DateTime
     */
    public function getGerminationDate()
    {
        return $this->germinationDate;
    }

    /**
     * Set tkw.
     *
     * @param float $tkw
     *
     * @return SeedBatch
     */
    public function setTkw($tkw)
    {
        $this->tkw = $tkw;

        return $this;
    }

    /**
     * Get tkw.
     *
     * @return float
     */
    public function getTkw()
    {
        return $this->tkw;
    }

    /**
     * Set tkwDate.
     *
     * @param \DateTime $tkwDate
     *
     * @return SeedBatch
     */
    public function setTkwDate($tkwDate)
    {
        $this->tkwDate = $tkwDate;

        return $this;
    }

    /**
     * Get tkwDate.
     *
     * @return \DateTime
     */
    public function getTkwDate()
    {
        return $this->tkwDate;
    }

    /**
     * Set seedFarm.
     *
     * @param \Sil\Bundle\SeedBatchBundle\Entity\SeedFarm $seedFarm
     *
     * @return SeedBatch
     */
    public function setSeedFarm(\Sil\Bundle\SeedBatchBundle\Entity\SeedFarm $seedFarm = null)
    {
        $this->seedFarm = $seedFarm;

        return $this;
    }

    /**
     * Get seedFarm.
     *
     * @return \Sil\Bundle\SeedBatchBundle\Entity\SeedFarm
     */
    public function getSeedFarm()
    {
        return $this->seedFarm;
    }

    /**
     * Set variety.
     *
     * @param \Sil\Bundle\VarietyBundle\Entity\Variety $variety
     *
     * @return SeedBatch
     */
    public function setVariety(\Sil\Bundle\VarietyBundle\Entity\Variety $variety = null)
    {
        $this->variety = $variety;

        return $this;
    }

    /**
     * Get variety.
     *
     * @return \Sil\Bundle\VarietyBundle\Entity\Variety
     */
    public function getVariety()
    {
        return $this->variety;
    }

    /**
     * Set producer.
     *
     * @param \Sil\Bundle\CRMBundle\Entity\Organism $producer
     *
     * @return SeedBatch
     */
    public function setProducer(\Sil\Bundle\CRMBundle\Entity\Organism $producer = null)
    {
        $this->producer = $producer;

        return $this;
    }

    /**
     * Set Organisme (Producer in fact).
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
     * Set plot.
     *
     * @param \Sil\Bundle\SeedBatchBundle\Entity\Plot $plot
     *
     * @return SeedBatch
     */
    public function setPlot(\Sil\Bundle\SeedBatchBundle\Entity\Plot $plot = null)
    {
        $this->plot = $plot;

        return $this;
    }

    /**
     * Get plot.
     *
     * @return \Sil\Bundle\SeedBatchBundle\Entity\Plot
     */
    public function getPlot()
    {
        return $this->plot;
    }

    /**
     * Set certifications.
     *
     * @param string $certifications
     *
     * @return SeedBatch
     */
    public function setCertifications($certifications)
    {
        $this->certifications = $certifications;

        return $this;
    }

    /**
     * Get certifications.
     *
     * @return string
     */
    public function getCertifications()
    {
        return $this->certifications;
    }

    /**
     * Set grossProducerWeight.
     *
     * @param int $grossProducerWeight
     *
     * @return SeedBatch
     */
    public function setGrossProducerWeight($grossProducerWeight)
    {
        $this->grossProducerWeight = $grossProducerWeight;

        return $this;
    }

    /**
     * Get grossProducerWeight.
     *
     * @return int
     */
    public function getGrossProducerWeight()
    {
        return $this->grossProducerWeight;
    }

    /**
     * Set grossDeliveredWeight.
     *
     * @param int $grossDeliveredWeight
     *
     * @return SeedBatch
     */
    public function setGrossDeliveredWeight($grossDeliveredWeight)
    {
        $this->grossDeliveredWeight = $grossDeliveredWeight;

        return $this;
    }

    /**
     * Get grossDeliveredWeight.
     *
     * @return int
     */
    public function getGrossDeliveredWeight()
    {
        return $this->grossDeliveredWeight;
    }

    /**
     * Set netScreenedWeight.
     *
     * @param int $netScreenedWeight
     *
     * @return SeedBatch
     */
    public function setNetScreenedWeight($netScreenedWeight)
    {
        $this->netScreenedWeight = $netScreenedWeight;

        return $this;
    }

    /**
     * Get netScreenedWeight.
     *
     * @return int
     */
    public function getNetScreenedWeight()
    {
        return $this->netScreenedWeight;
    }

    /**
     * Set toScreenWeight.
     *
     * @param int $toScreenWeight
     *
     * @return SeedBatch
     */
    public function setToScreenWeight($toScreenWeight)
    {
        $this->toScreenWeight = $toScreenWeight;

        return $this;
    }

    /**
     * Get toScreenWeight.
     *
     * @return int
     */
    public function getToScreenWeight()
    {
        return $this->toScreenWeight;
    }

    /**
     * Set deliveryDate.
     *
     * @param \DateTime $deliveryDate
     *
     * @return SeedBatch
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    /**
     * Get deliveryDate.
     *
     * @return \DateTime
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * Set deliveryNote.
     *
     * @param bool $deliveryNote
     *
     * @return SeedBatch
     */
    public function setDeliveryNote($deliveryNote)
    {
        $this->deliveryNote = $deliveryNote;

        return $this;
    }

    /**
     * Get deliveryNote.
     *
     * @return bool
     */
    public function getDeliveryNote()
    {
        return $this->deliveryNote;
    }
}
