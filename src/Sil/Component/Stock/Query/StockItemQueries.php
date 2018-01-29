<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Stock\Query;

use Sil\Component\Stock\Model\StockItemInterface;
use Sil\Component\Stock\Model\Location;
use Sil\Component\Stock\Model\Movement;
use Sil\Component\Uom\Model\Uom;
use Sil\Component\Uom\Model\UomQty;
use Sil\Component\Stock\Repository\StockUnitRepositoryInterface;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class StockItemQueries implements StockItemQueriesInterface
{
    /**
     * @var StockUnitRepositoryInterface
     */
    private $stockUnitRepository;

    /**
     * @param StockUnitRepositoryInterface $stockUnitRepository
     */
    public function __construct(StockUnitRepositoryInterface $stockUnitRepository)
    {
        $this->stockUnitRepository = $stockUnitRepository;
    }

    /**
     * @param StockItemInterface $item
     *
     * @return UomQty
     */
    public function getQty(StockItemInterface $item): UomQty
    {
        $units = $this->stockUnitRepository
            ->findByStockItem($item);

        return $this->computeQtyForUnits($item->getUom(), $units);
    }

    /**
     * @param StockItemInterface $item
     *
     * @return UomQty
     */
    public function getReservedQty(StockItemInterface $item): UomQty
    {
        $units = $this->stockUnitRepository
            ->findReservedByStockItem($item);

        return $this->computeQtyForUnits($item->getUom(), $units);
    }

    /**
     * @param StockItemInterface $item
     *
     * @return UomQty
     */
    public function getAvailableQty(StockItemInterface $item): UomQty
    {
        $units = $this->stockUnitRepository
            ->findAvailableByStockItem($item);

        return $this->computeQtyForUnits($item->getUom(), $units);
    }

    /**
     * @param StockItemInterface $item
     *
     * @return UomQty
     */
    public function getReservedQtyByMovement(StockItemInterface $item,
        Movement $mvt): UomQty
    {
        $units = $this->stockUnitRepository
            ->findBy(['stockItem' => $item, 'reservationMovement' => $mvt]);

        return $this->computeQtyForUnits($item->getUom(), $units);
    }

    /**
     * @param StockItemInterface $item
     * @param Location           $location
     *
     * @return UomQty
     */
    public function getQtyByLocation(StockItemInterface $item,
        Location $location): UomQty
    {
        $units = $this->stockUnitRepository
            ->findByStockItemAndLocation($item, $location);

        return $this->computeQtyForUnits($item->getUom(), $units);
    }

    /**
     * @param Uom               $uom
     * @param array|StockUnit[] $stockUnits
     *
     * @return UomQty
     */
    protected function computeQtyForUnits(Uom $uom, array $stockUnits): UomQty
    {
        $result = new UomQty($uom, 0);
        $unitQties = array_map(function ($q) {
            return $q->getQty();
        }, $stockUnits);

        $result = array_reduce($unitQties, function ($carry, $item) {
            return $carry->increasedBy($item);
        }, $result);

        return $result;
    }
}
