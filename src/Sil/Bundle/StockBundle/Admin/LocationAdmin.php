<?php

declare(strict_types=1);

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Admin;

use Blast\Bundle\ResourceBundle\Sonata\Admin\ResourceAdmin;
use Sil\Bundle\StockBundle\Domain\Entity\Location;
use Sil\Bundle\StockBundle\Domain\Entity\StockItem;
use Sil\Bundle\StockBundle\Domain\Query\StockItemQueriesInterface;
use Sil\Bundle\StockBundle\Domain\Repository\StockItemRepositoryInterface;
use Sil\Bundle\StockBundle\Domain\Repository\StockUnitRepositoryInterface;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class LocationAdmin extends ResourceAdmin
{
    protected $baseRouteName = 'admin_stock_location';
    protected $baseRoutePattern = 'stock/location';

    /**
     * @var StockItemRepositoryInterface
     */
    protected $stockItemRepository;

    /**
     * @var StockUnitRepositoryInterface
     */
    protected $stockUnitRepository;

    /**
     * @var StockItemQueriesInterface
     */
    protected $stockItemQueries;

    /**
     * @param Location $location
     *
     * @return array|Stockitem[]
     */
    public function getStockUnitsByLocation(Location $location)
    {
        return $this->getStockUnitRepository()->findByLocation($location,
                ['createdAt' => 'DESC'], 10);
    }

    public function getQtyByItemAndLocation(StockItem $item, Location $location)
    {
        return $this->getStockItemQueries()->getQtyByLocation($item, $location);
    }

    public function getStockUnitRepository(): StockUnitRepositoryInterface
    {
        return $this->stockUnitRepository;
    }

    public function getStockItemRepository(): StockItemRepositoryInterface
    {
        return $this->stockItemRepository;
    }

    public function getStockItemQueries(): StockItemQueriesInterface
    {
        return $this->stockItemQueries;
    }

    public function setStockUnitRepository(StockUnitRepositoryInterface $stockUnitRepository)
    {
        $this->stockUnitRepository = $stockUnitRepository;
    }

    public function setStockItemRepository(StockItemRepositoryInterface $stockItemRepository)
    {
        $this->stockItemRepository = $stockItemRepository;
    }

    public function setStockItemQueries(StockItemQueriesInterface $stockItemQueries)
    {
        $this->stockItemQueries = $stockItemQueries;
    }

    public function toString($location)
    {
        return sprintf('[%s] %s', $location->getCode(), $location->getName());
    }
}
