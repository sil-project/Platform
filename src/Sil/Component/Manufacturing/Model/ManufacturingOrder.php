<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Manufacturing\Model;

use Blast\Component\Resource\Model\ResourceInterface;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Timestampable;
use Sil\Component\Stock\Model\Location;
use Sil\Component\Stock\Model\ProgressState;
use Sil\Component\Stock\Model\Operation;
use Sil\Component\Stock\Model\ProgressStateAwareInterface;
use Sil\Component\Stock\Model\ProgressStateAwareTrait;
use Sil\Component\Stock\Model\BatchInterface;
use Sil\Bundle\UomBundle\Entity\Uom;
use Sil\Component\Uom\Model\UomQty;
use DateTimeInterface;
use DateTime;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class ManufacturingOrder implements ProgressStateAwareInterface, ResourceInterface
{
    use Guidable,
        Timestampable,
        ProgressStateAwareTrait;
    /**
     * @var string
     */
    protected $code;

    /**
     * @var DateTimeInterface
     */
    protected $expectedAt;

    /**
     * @var DateTimeInterface
     */
    protected $completedAt;

    /**
     * @var float
     */
    protected $qtyValue = 0;

    /**
     * @var Uom
     */
    protected $qtyUom;

    /**
     * @var Bom
     */
    protected $bom;

    /**
     * @var BatchInterface
     */
    protected $batch;

    /**
     * @var Location
     */
    protected $destLocation;

    /**
     * @var string
     */
    protected $stateValue;

    /**
     * @var Operation
     */
    protected $inputOperation;

    /**
     * @var Operation
     */
    protected $outputOperation;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->expectedAt = new DateTime();
        $this->setState(ProgressState::draft());
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return DateTimeInterface
     */
    public function getExpectedAt(): DateTimeInterface
    {
        return $this->expectedAt;
    }

    /**
     * @param DateTimeInterface $expectedAt
     */
    public function setExpectedAt(DateTimeInterface $expectedAt): void
    {
        $this->expectedAt = $expectedAt;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCompletedAt(): ?DateTimeInterface
    {
        return $this->completedAt;
    }

    /**
     * @param DateTimeInterface $completedAt
     */
    public function setCompletedAt(DateTimeInterface $completedAt): void
    {
        $this->completedAt = $completedAt;
    }

    /**
     * @return UomQty
     */
    public function getQty(): ?UomQty
    {
        if (null == $this->qtyUom) {
            return null;
        }

        return new UomQty($this->qtyUom, floatval($this->qtyValue));
    }

    /**
     * @param UomQty $qty
     */
    public function setQty(UomQty $qty): void
    {
        $this->qtyValue = $qty->getValue();
        $this->qtyUom = $qty->getUom();
    }

    public function getBom(): ?Bom
    {
        return $this->bom;
    }

    public function setBom(?Bom $bom): void
    {
        $this->bom = $bom;
    }

    /**
     * @return BatchInterface|null
     */
    public function getBatch(): ?BatchInterface
    {
        return $this->batch;
    }

    /**
     * @param BatchInterface|null $batch
     */
    public function setBatch(?BatchInterface $batch): void
    {
        $this->batch = $batch;
    }

    /**
     * @return Location
     */
    public function getDestLocation(): ?Location
    {
        return $this->destLocation;
    }

    /**
     * @param Location $srcLocation
     */
    public function setDestLocation(Location $destLocation): void
    {
        $this->destLocation = $destLocation;
    }

    /**
     * @return ProgressState
     */
    public function getState(): ProgressState
    {
        return new ProgressState($this->stateValue);
    }

    /**
     * @param ProgressState $state
     */
    private function setState(ProgressState $state): void
    {
        $this->stateValue = $state->getValue();
    }

    public function getInputOperation(): ?Operation
    {
        return $this->inputOperation;
    }

    public function setInputOperation(Operation $inputOperation): void
    {
        $this->inputOperation = $inputOperation;
    }

    public function setOutputOperation(Operation $outputOperation): void
    {
        $this->outputOperation = $outputOperation;
    }

    public function getOutputOperation(): ?Operation
    {
        return $this->outputOperation;
    }

    /**
     * @return bool
     */
    public function isFullyReserved(): bool
    {
        return $this->getInputOperation()->isFullyReserved();
    }

    public function hasReservedStockUnits(): bool
    {
        return $this->getInputOperation()->hasReservedStockUnits();
    }
}
