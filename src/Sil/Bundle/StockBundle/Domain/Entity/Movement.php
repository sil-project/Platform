<?php

declare(strict_types=1);

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Domain\Entity;

use DateTimeInterface;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Timestampable;
use DomainException;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class Movement implements ProgressStateAwareInterface
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
    protected $completedAt;

    /**
     * @var DateTimeInterface
     */
    protected $expectedAt;

    /**
     * @var StockItemInterface
     */
    protected $stockItem;

    /**
     * @var float
     */
    protected $qtyValue = 0;

    /**
     * @var Uom
     */
    protected $qtyUom;

    /**
     * @var Operation
     */
    protected $operation;

    /**
     * @var Location
     */
    protected $srcLocation;

    /**
     * @var Location
     */
    protected $destLocation;

    /**
     * @var string
     */
    protected $stateValue;

    /**
     * @var BatchInterface
     */
    protected $batch;

    /**
     * @var Collection|StockUnit[]
     */
    protected $reservedStockUnits;

    /**
     * @param string             $code
     * @param StockItemInterface $stockItem
     * @param UomQty             $qty
     */
    public static function createDefault(string $code, StockItemInterface $item,
        UomQty $qty)
    {
        $o = new self();
        $o->code = $code;
        $o->stockItem = $item;
        $o->setQty($qty);

        return $o;
    }

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->expectedAt = new DateTime();
        $this->setState(ProgressState::draft());
        $this->reservedStockUnits = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCompletedAt(): ?DateTimeInterface
    {
        return $this->completedAt;
    }

    /**
     * @return DateTimeInterface
     */
    public function getExpectedAt(): DateTimeInterface
    {
        return $this->expectedAt;
    }

    /**
     * @return Operation
     */
    public function getOperation(): Operation
    {
        return $this->operation;
    }

    /**
     * @return Location
     */
    public function getSrcLocation(): ?Location
    {
        return $this->srcLocation ?: $this->getOperation()->getSrcLocation();
    }

    /**
     * @return Location
     */
    public function getDestLocation(): ?Location
    {
        return $this->destLocation ?: $this->getOperation()->getDestLocation();
    }

    /**
     * @return ProgressState
     */
    public function getState(): ProgressState
    {
        return new ProgressState($this->stateValue);
    }

    /**
     * @return StockItemInterface
     */
    public function getStockItem(): ?StockItemInterface
    {
        return $this->stockItem;
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
     * @return BatchInterface|null
     */
    public function getBatch(): ?BatchInterface
    {
        return $this->batch;
    }

    /**
     * @return Collection
     */
    public function getReservedStockUnits(): Collection
    {
        return $this->reservedStockUnits;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->code = $code;
    }

    /**
     * @param DateTimeInterface $completedAt
     */
    public function setCompletedAt(DateTimeInterface $completedAt): void
    {
        $this->completedAt = $completedAt;
    }

    /**
     * @param DateTimeInterface $expectedAt
     */
    public function setExpectedAt(DateTimeInterface $expectedAt): void
    {
        $this->expectedAt = $expectedAt;
    }

    /**
     * @param Operation $operation
     */
    public function setOperation(Operation $operation)
    {
        $this->operation = $operation;
    }

    /**
     * @param Location $srcLocation
     */
    public function setSrcLocation(Location $srcLocation): void
    {
        $this->srcLocation = $srcLocation;
    }

    /**
     * @param Location $destLocation
     */
    public function setDestLocation(Location $destLocation): void
    {
        $this->destLocation = $destLocation;
    }

    /**
     * @param StockItemInterface $stockItem
     */
    public function setStockItem(StockItemInterface $stockItem): void
    {
        $this->stockItem = $stockItem;
    }

    /**
     * @param UomQty $qty
     */
    public function setQty(UomQty $qty): void
    {
        $this->qtyValue = $qty->getValue();
        $this->qtyUom = $qty->getUom();
    }

    /**
     * @param BatchInterface|null $batch
     */
    public function setBatch(?BatchInterface $batch): void
    {
        $this->batch = $batch;
    }

    /**
     * @param ProgressState $state
     */
    private function setState(ProgressState $state)
    {
        $this->stateValue = $state->getValue();
    }

    /**
     * @return bool
     */
    public function withBatchTracking(): bool
    {
        return null !== $this->batch;
    }

    /**
     * @param StockUnit $unit
     *
     * @throws DomainException
     */
    public function reserve(StockUnit $unit): void
    {
        if ($unit->isReserved()) {
            throw new \DomainException('The StockUnit is already reserved');
        }
        $unit->beReservedByMovement($this);
        $this->setSrcLocation($unit->getLocation());
        $this->reservedStockUnits->add($unit);
    }

    /**
     * @param StockUnit $unit
     *
     * @throws DomainException
     */
    public function unreserve(StockUnit $unit): void
    {
        if (!$unit->isReserved()) {
            throw new \DomainException('The StockUnit is not reserved and cannot be unreserved');
        }
        if (!$this->reservedStockUnits->contains($unit)) {
            throw new \DomainException('The StockUnit is not reserved by this Movement');
        }
        $unit->beUnreserved();
        $this->reservedStockUnits->removeElement($unit);
    }

    public function unreserveAllUnits(): void
    {
        if ($this->isCancel() || $this->isDone()) {
            throw new DomainException('Movement which is '
                    . 'cancel or done connot be unreserved');
        }

        if (!$this->hasReservedStockUnits()) {
            return;
        }

        foreach ($this->reservedStockUnits as $unit) {
            $this->unreserve($unit);
        }
    }

    /**
     * @return UomQty
     */
    public function getReservedQty(): UomQty
    {
        $qty = $this->getQty()->copyWithValue(0);

        foreach ($this->reservedStockUnits as $unit) {
            $qty = $qty->increasedBy($unit->getQty());
        }

        return $qty;
    }

    /**
     * @return UomQty
     */
    public function getRemainingQtyToBeReserved(): UomQty
    {
        return $this->getQty()->decreasedBy($this->getReservedQty());
    }

    /**
     * @return bool
     */
    public function isFullyReserved(): bool
    {
        return $this->getRemainingQtyToBeReserved()->isZero();
    }

    public function hasReservedStockUnits(): bool
    {
        return !$this->reservedStockUnits->isEmpty();
    }

    public function diplayString()
    {
        return '[' . $this->getCode() . '] ' . $this->getStockItem()->getCode()
            . ' ' . $this->getStockItem()->getName() . ' ' . $this->getQty();
    }
}
