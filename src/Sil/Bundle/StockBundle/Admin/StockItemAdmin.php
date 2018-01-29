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
use Sil\Component\Stock\Query\StockItemQueriesInterface;
use Sil\Component\Stock\Repository\LocationRepositoryInterface;
use Sil\Component\Stock\Model\StockItemInterface;
use Sil\Component\Stock\Model\Location;
use Sil\Component\Stock\Model\LocationType;

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

    public function getNewInstance()
    {
        $object = parent::getNewInstance();
        if (method_exists(get_class($object), 'setCurrentLocale')) {
            $defaultLocale = $this->getConfigurationPool()->getContainer()->get('sylius.locale_provider')->getDefaultLocaleCode();
            $object->setCurrentLocale($defaultLocale);
            $object->setFallbackLocale($defaultLocale);
        }

        foreach ($this->getExtensions() as $extension) {
            $extension->alterNewInstance($this, $object);
        }

        return $object;
    }

    public function getQtyByItemAndLocation(StockItemInterface $item, Location $location)
    {
        return $this->getStockItemQueries()->getQtyByLocation($item, $location);
    }

    public function getUnitsByItemAndLocation(StockItemInterface $item, Location $location)
    {
        return $this->getStockUnitRepository()->findByStockItemAndLocation($item, $location);
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

    public function setLocationRepository(LocationRepositoryInterface $stockUnitRepository)
    {
        $this->locationRepository = $stockUnitRepository;
    }

    public function getStockUnitRepository(): StockUnitRepositoryInterface
    {
        return $this->stockUnitRepository;
    }

    public function setStockUnitRepository(StockUnitRepositoryInterface $stockUnitRepository)
    {
        $this->stockUnitRepository = $stockUnitRepository;
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
