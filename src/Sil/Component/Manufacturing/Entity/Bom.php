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
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Timestampable;
use Sil\Component\Stock\Model\StockItemInterface;
use Sil\Bundle\UomBundle\Entity\Uom;
use Sil\Component\Uom\Model\UomQty;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class Bom implements ResourceInterface
{
    use Guidable,
        Timestampable;

    /**
     * @var string
     */
    protected $code;

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
     * @var Collection|BomLine[]
     */
    protected $lines;

    public function __construct()
    {
        $this->lines = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
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
     * @param UomQty $qty
     */
    public function setQty(UomQty $qty): void
    {
        $this->qtyValue = $qty->getValue();
        $this->qtyUom = $qty->getUom();
    }

    /**
     * @return Collection|BomLine[]
     */
    public function getLines(): Collection
    {
        return $this->lines;
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
    public function setStockItem(StockItemInterface $stockItem)
    {
        $this->stockItem = $stockItem;
    }

    /**
     * @param Collection|BomLine[] $lines
     */
    public function setLines(Collection $lines)
    {
        $this->lines = $lines;
    }

    /**
     * @param BomLine $line
     *
     * @return bool
     */
    public function hasLine(BomLine $line): bool
    {
        return $this->lines->contains($line);
    }

    /**
     * @param BomLine $line
     *
     * @throws InvalidArgumentException
     */
    public function addLine(BomLine $line): void
    {
        if ($this->hasLine($line)) {
            throw new \InvalidArgumentException(
                'The same BomLine cannot be added twice');
        }

        $line->setBom($this);
        $this->lines->add($line);
    }

    /**
     * @param BomLine $line
     *
     * @throws InvalidArgumentException
     */
    public function removeLine(BomLine $line): void
    {
        if (!$this->hasLine($line)) {
            throw new \InvalidArgumentException(
                'The BomLine is not part of this Bom and cannot be removed from there');
        }
        $this->lines->removeElement($line);
    }
}
