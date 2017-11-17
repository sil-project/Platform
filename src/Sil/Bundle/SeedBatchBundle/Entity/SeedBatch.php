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

use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Descriptible;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Loggable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Searchable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Timestampable;
use Sil\Bundle\CRMBundle\Entity\OrganismInterface;
use Sil\Bundle\VarietyBundle\Entity\VarietyInterface;

/**
 * SeedBatch.
 */
class SeedBatch implements SeedBatchInterface
{
    use BaseEntity,
        Timestampable,
        Loggable,
        Descriptible,
        Searchable;

    /**
     * @var VarietyInterface
     */
    protected $variety;

    /**
     * @var OrganismInterface
     */
    protected $producer;

    /**
     * @var Plot
     */
    protected $plot;

    /**
     * @var SeedFarm
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
     * @return SeedBatchInterface
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
     * @return SeedBatchInterface
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
     * @return SeedBatchInterface
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
     * @return SeedBatchInterface
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
     * @return SeedBatchInterface
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
     * @param SeedFarm $seedFarm
     *
     * @return SeedBatchInterface
     */
    public function setSeedFarm(SeedFarm $seedFarm = null)
    {
        $this->seedFarm = $seedFarm;

        return $this;
    }

    /**
     * Get seedFarm.
     *
     * @return SeedFarm
     */
    public function getSeedFarm()
    {
        return $this->seedFarm;
    }

    /**
     * Set variety.
     *
     * @param VarietyInterface $variety
     *
     * @return SeedBatchInterface
     */
    public function setVariety(VarietyInterface $variety = null)
    {
        $this->variety = $variety;

        return $this;
    }

    /**
     * Get variety.
     *
     * @return VarietyInterface
     */
    public function getVariety()
    {
        return $this->variety;
    }

    /**
     * Set producer.
     *
     * @param OrganismInterface $producer
     *
     * @return SeedBatchInterface
     */
    public function setProducer(OrganismInterface $producer = null)
    {
        $this->producer = $producer;

        return $this;
    }

    /**
     * Set Organisme (Producer in fact).
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
     * Set plot.
     *
     * @param Plot $plot
     *
     * @return SeedBatchInterface
     */
    public function setPlot(Plot $plot = null)
    {
        $this->plot = $plot;

        return $this;
    }

    /**
     * Get plot.
     *
     * @return Plot
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
     * @return SeedBatchInterface
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
     * @return SeedBatchInterface
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
     * @return SeedBatchInterface
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
     * @return SeedBatchInterface
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
     * @return SeedBatchInterface
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
     * @return SeedBatchInterface
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
     * @return SeedBatchInterface
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
