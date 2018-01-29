<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ManufacturingBundle\Domain\Entity;

use Blast\Component\Resource\Model\ResourceInterface;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;
use Sil\Component\Stock\Model\StockItemInterface;
use Sil\Component\Stock\Model\BatchInterface;
use Sil\Component\Stock\Model\Location;
use Sil\Bundle\UomBundle\Entity\Uom;
use Sil\Component\Uom\Model\UomQty;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class BomLine implements ResourceInterface
{
    use Guidable;

    /**
     * @var Bom
     */
    protected $bom;

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
     * @var Location
     */
    protected $srcLocation;

    /**
     * @var BatchInterface
     */
    protected $batch;

    /**
     * @return Bom
     */
    public function getBom(): ?Bom
    {
        return $this->bom;
    }

    /**
     * @param Bom $bom
     */
    public function setBom(Bom $bom)
    {
        $this->bom = $bom;
    }

    /**
     * @return StockItemInterface
     */
    public function getStockItem(): ?StockItemInterface
    {
        return $this->stockItem;
    }

    /**
     * @param StockItemInterface $stockItem
     */
    public function setStockItem(StockItemInterface $stockItem)
    {
        $this->stockItem = $stockItem;
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

    /**
     * @return Location
     */
    public function getSrcLocation(): ?Location
    {
        return $this->srcLocation;
    }

    /**
     * @param Location $srcLocation
     */
    public function setSrcLocation(Location $srcLocation): void
    {
        $this->srcLocation = $srcLocation;
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
}
