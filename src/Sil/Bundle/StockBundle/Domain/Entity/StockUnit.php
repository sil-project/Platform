<?php

declare(strict_types=1);

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Domain\Entity;

use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Timestampable;
use DomainException;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class StockUnit
{
    use Guidable,
        Timestampable;

    /**
     * @var string
     */
    private $code;

    /**
     * @var float
     */
    private $qtyValue = 0;

    /**
     * @var Uom
     */
    private $qtyUom;

    /**
     * @var StockItemInterface
     */
    private $stockItem;

    /**
     * @var Location
     */
    private $location;

    /**
     * @var BatchInterface
     */
    private $batch;

    /**
     * @var Movement
     */
    private $reservationMovement;

    /**
     * @param string             $code
     * @param StockItemInterface $item
     * @param UomQty             $qty
     * @param Location           $location
     * @param BatchInterface     $batch
     */
    public static function createDefault($code, StockItemInterface $item,
        UomQty $qty, Location $location, BatchInterface $batch = null)
    {
        $o = new self();
        $o->code = $code;
        $o->setQty($qty->convertTo($item->getUom()));
        $o->stockItem = $item;
        $o->location = $location;
        $o->batch = $batch;
        $location->addStockUnit($o);

        return $o;
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
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
     * @return StockItemInterface
     */
    public function getStockItem(): ?StockItemInterface
    {
        return $this->stockItem;
    }

    /**
     * @return Location
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * @return BatchInterface|null
     */
    public function getBatch(): ?BatchInterface
    {
        return $this->batch;
    }

    /**
     * @return Movement|null
     */
    public function getReservationMovement(): ?Movement
    {
        return $this->reservationMovement;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @param StockItemInterface $stockItem
     */
    public function setStockItem(StockItemInterface $stockItem): void
    {
        $this->stockItem = $stockItem;
    }

    /**
     * @param Location $location
     */
    public function setLocation(Location $location): void
    {
        $this->location = $location;
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
     * @param Movement $reservationMovement
     *
     * @throws DomainException
     */
    public function beReservedByMovement(Movement $reservationMovement): void
    {
        if ($this->isReserved()) {
            throw new DomainException('The StockUnit is already reserved');
        }
        $this->reservationMovement = $reservationMovement;
    }

    public function beUnreserved(): void
    {
        $this->reservationMovement = null;
    }

    /**
     * @return bool
     */
    public function isReserved(): bool
    {
        return null !== $this->reservationMovement;
    }
}
