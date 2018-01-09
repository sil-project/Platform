<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Admin;

use Blast\Bundle\ResourceBundle\Sonata\Admin\ResourceAdmin;
use Sil\Bundle\StockBundle\Domain\Query\StockItemQueriesInterface;
use Sil\Bundle\StockBundle\Domain\Repository\LocationRepositoryInterface;
use Sil\Bundle\StockBundle\Domain\Entity\StockItemInterface;
use Sil\Bundle\StockBundle\Domain\Entity\Location;
use Sil\Bundle\StockBundle\Domain\Entity\LocationType;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class StockItemAdmin extends ResourceAdmin
{
    protected $baseRouteName = 'admin_stock_item';
    protected $baseRoutePattern = 'stock/item';

    /**
     * @var StockItemQueriesInterface
     */
    protected $stockItemQueries;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    public function getQtyByItemAndLocation(StockItemInterface $item, Location $location)
    {
        return $this->getStockItemQueries()->getQtyByLocation($item, $location);
    }

    public function getUnitsByLocation(StockItemInterface $item, Location $location)
    {
        return $this->getStockItemQueries()->getUnitsByLocationAndGroupedByBatch($item, $location);
    }

    public function getLocationsByItem(StockItemInterface $item)
    {
        return $this->getLocationRepository()->findByOwnedItem($item, LocationType::INTERNAL);
    }

    public function getInStockQty(StockItemInterface $item)
    {
        return $this->getStockItemQueries()->getQty($item);
    }

    public function getInStockQtyByLocation(StockItemInterface $item, Location $location)
    {
        return $this->getStockItemQueries()->getQtyByLocation($item, $location);
    }

    public function getReservedQty(StockItemInterface $item)
    {
        return $this->getStockItemQueries()->getReservedQty($item);
    }

    public function getAvailableQty(StockItemInterface $item)
    {
        return $this->getStockItemQueries()->getAvailableQty($item);
    }

    public function getLocationRepository(): LocationRepositoryInterface
    {
        return $this->locationRepository;
    }

    public function setLocationRepository(LocationRepositoryInterface $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    public function getStockItemQueries(): StockItemQueriesInterface
    {
        return $this->stockItemQueries;
    }

    public function setStockItemQueries(StockItemQueriesInterface $stockItemQueries)
    {
        $this->stockItemQueries = $stockItemQueries;
    }

    public function toString($item)
    {
        return sprintf('[%s] %s', $item->getCode(), $item->getName());
    }
}
