<?php

declare(strict_types=1);

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Domain\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\NestedTreeable;
use InvalidArgumentException;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class Location
{
    use Guidable;
    use NestedTreeable;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $typeValue;

    /**
     * @var Warehouse
     */
    protected $warehouse;

    /**
     * @var bool
     */
    protected $managed = true;

    /**
     * @var Collection|StockUnit[]
     */
    protected $stockUnits;

    public static function createDefault(string $code, string $name,
        LocationType $type)
    {
        $o = new self();
        $o->code = $code;
        $o->name = $name;
        $o->setType($type);

        return $o;
    }

    /**
     * @param string $code
     * @param string $name
     */
    public function __construct()
    {
        $this->setType(LocationType::internal());
        $this->stockUnits = new ArrayCollection();

        //from NestedTreeable
        $this->initCollections();
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return LocationType
     */
    public function getType(): LocationType
    {
        return LocationType::{$this->typeValue}();
    }

    /**
     * @return Warehouse
     */
    public function getWarehouse(): ?Warehouse
    {
        if ($this->hasParent()) {
            return $this->getParent()->getWarehouse();
        }

        return $this->warehouse;
    }

    /**
     * @return bool
     */
    public function isManaged(): bool
    {
        return $this->managed;
    }

    /**
     * @return Location
     */
    public function getParent(): ?Location
    {
        return $this->getTreeParent();
    }

    /**
     * @return bool
     */
    public function hasParent(): bool
    {
        return null !== $this->getParent();
    }

    /**
     * @return Collection|Location[]
     */
    public function getChildren(): Collection
    {
        return $this->getTreeChildren();
    }

    /**
     * @return Collection
     */
    public function getOwnedStockUnits(): Collection
    {
        return $this->stockUnits;
    }

    /**
     * @return Collection
     */
    public function getStockUnits(): Collection
    {
        $result = [];
        if (!$this->stockUnits->isEmpty()) {
            $result = array_merge($result, $this->stockUnits->toArray());
        }
        foreach ($this->getChildren() as $child) {
            $result = array_merge($result, $child->getStockUnits()->toArray());
        }

        return new ArrayCollection($result);
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @param LocationType $type
     */
    public function setType(LocationType $type): void
    {
        $this->typeValue = $type->getValue();
    }

    /**
     * @param Warehouse|null $warehouse
     */
    public function setWarehouse(?Warehouse $warehouse): void
    {
        if ($this->hasParent()) {
            throw new InvalidArgumentException(
                'The warehouse cannot be update for a child location');
        }
        $this->warehouse = $warehouse;
    }

    /**
     * @param bool $isManaged
     */
    public function setManaged(bool $isManaged): void
    {
        $this->managed = $isManaged;
    }

    /**
     * @param Location $parent
     */
    public function setParent(?Location $parent): void
    {
        $this->setTreeParent($parent);
    }

    /**
     * @param StockUnit $unit
     *
     * @return bool
     */
    public function hasChild(Location $location): bool
    {
        return $this->getChildren()->contains($location);
    }

    /**
     * @param Location $location
     *
     * @throws InvalidArgumentException
     */
    public function addChild(Location $location): void
    {
        if ($this->hasChild($location)) {
            throw new InvalidArgumentException(
                'The same Location cannot be added twice');
        }

        $location->setWarehouse(null);
        $location->setParent($this);

        $this->addTreeChild($location);
    }

    /**
     * @param Location $location
     *
     * @throws InvalidArgumentException
     */
    public function removeChild(Location $location): void
    {
        if (!$this->hasChild($location)) {
            throw new InvalidArgumentException(
                'The Location is not a child and cannot be removed from there');
        }
        $location->setParent(null);
        $this->getChildren()->removeElement($location);
    }

    /**
     * @param Collection $stockUnits
     */
    public function setStockUnits(Collection $stockUnits): void
    {
        $this->stockUnits = $stockUnits;
    }

    /**
     * @param StockUnit $unit
     *
     * @return bool
     */
    public function hasStockUnit(StockUnit $unit): bool
    {
        if ($this->hasOwnedStockUnit($unit)) {
            return true;
        }

        foreach ($this->getChildren() as $child) {
            if ($child->hasStockUnit($unit)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param StockUnit $unit
     *
     * @return bool
     */
    public function hasOwnedStockUnit(StockUnit $unit): bool
    {
        return $this->stockUnits->contains($unit);
    }

    /**
     * @param StockUnit $unit
     *
     * @throws InvalidArgumentException
     */
    public function addStockUnit(StockUnit $unit): void
    {
        if ($this->hasStockUnit($unit)) {
            throw new InvalidArgumentException(
                'The same StockUnit cannot be added twice');
        }

        $this->stockUnits->add($unit);
    }

    public function removeStockUnit(StockUnit $unit): void
    {
        if (!$this->hasStockUnit($unit)) {
            throw new InvalidArgumentException(
                'The StockUnit is not at this Location hierarchy and cannot be removed from there');
        }
        $unit->getLocation()->removeOwnedStockUnit($unit);
    }

    public function removeOwnedStockUnit(StockUnit $unit): void
    {
        if (!$this->hasOwnedStockUnit($unit)) {
            throw new InvalidArgumentException(
                'The StockUnit is not at this specific Location and cannot be removed from there');
        }
        $this->stockUnits->removeElement($unit);
    }

    public function getIndentedName(): string
    {
        return str_repeat('  ', $this->getTreeLvl()) . $this->getName();
    }

    public function getCodePath()
    {
        $path = '';
        if ($this->hasParent()) {
            $path .= $this->getParent()->getCodePath();
        } else {
            $path .= $this->getWarehouse()->getCode();
        }

        return $path . '/' . $this->getCode();
    }

    /*
     * @deprecated
     * @param StockItemInterface $stockItem
     * @return boolean

      public function hasStockItem(StockItemInterface $stockItem): boolean
      {
      return $this->stockUnits->exists(
      function($i, $unit) use($stockItem) {
      return $unit->getStockItem() == $stockItem;
      });
      } */
    /*
     * @deprecated
     * @return array|StockItemInterface[]

      public function getStockItems(): array
      {
      $items = array_map(
      $this->stockUnits->toArray(),
      function($unit) {
      return $unit->getStockItem();
      });

      return array_unique($items);
      } */
}
