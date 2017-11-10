<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Test\Unit;

use PHPUnit\Framework\TestCase;
use Sil\Bundle\StockBundle\Domain\Entity\UomType;
use Sil\Bundle\StockBundle\Domain\Entity\Uom;
use Sil\Bundle\StockBundle\Domain\Entity\UomQty;
use Sil\Bundle\StockBundle\Domain\Entity\Warehouse;
use Sil\Bundle\StockBundle\Domain\Entity\Location;
use Sil\Bundle\StockBundle\Domain\Entity\StockItem;
use Sil\Bundle\StockBundle\Domain\Entity\OutputStrategy;
use Sil\Bundle\StockBundle\Domain\Repository\StockUnitRepositoryInterface;
use Sil\Bundle\StockBundle\Domain\Repository\InMemory\StockUnitRepository;
use Sil\Bundle\StockBundle\Domain\Repository\MovementRepositoryInterface;
use Sil\Bundle\StockBundle\Domain\Repository\InMemory\MovementRepository;
use Sil\Bundle\StockBundle\Domain\Repository\OperationRepositoryInterface;
use Sil\Bundle\StockBundle\Domain\Repository\InMemory\OperationRepository;
use Sil\Bundle\StockBundle\Domain\Repository\StockItemRepositoryInterface;
use Sil\Bundle\StockBundle\Domain\Repository\InMemory\StockItemRepository;
use Sil\Bundle\StockBundle\Domain\Service\MovementServiceInterface;
use Sil\Bundle\StockBundle\Domain\Service\MovementService;
use Sil\Bundle\StockBundle\Domain\Service\OperationServiceInterface;
use Sil\Bundle\StockBundle\Domain\Service\OperationService;
use Sil\Bundle\StockBundle\Domain\Service\UomService;
use Sil\Bundle\StockBundle\Domain\Service\UomServiceInterface;
use Sil\Bundle\StockBundle\Domain\Query\StockItemQueriesInterface;
use Sil\Bundle\StockBundle\Domain\Query\StockItemQueries;
use Sil\Bundle\StockBundle\Domain\Factory\StockUnitFactoryInterface;
use Sil\Bundle\StockBundle\Domain\Factory\StockUnitFactory;
use Sil\Bundle\StockBundle\Domain\Factory\MovementFactoryInterface;
use Sil\Bundle\StockBundle\Domain\Factory\MovementFactory;
use Sil\Bundle\StockBundle\Domain\Factory\OperationFactoryInterface;
use Sil\Bundle\StockBundle\Domain\Factory\OperationFactory;
use Sil\Bundle\StockBundle\Domain\Generator\StockUnitCodeGenerator;
use Sil\Bundle\StockBundle\Domain\Generator\MovementCodeGenerator;
use Sil\Bundle\StockBundle\Domain\Generator\OperationCodeGenerator;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class AbstractStockTestCase extends TestCase
{
    /**
     * @var UomType
     */
    protected $uomTypeMass;

    /**
     * @var Uom
     */
    protected $uomKg;

    /**
     * @var Uom
     */
    protected $uomGr;

    /**
     * @var Warehouse
     */
    protected $ws;

    /**
     * @var Location
     */
    protected $whLocSrc;

    /**
     * @var Location
     */
    protected $whLocDest;

    /**
     * @var StockItem
     */
    protected $stockItem;

    /**
     * @var StockUnitRepositoryInterface
     */
    protected $unitRepo;

    /**
     * @var MovementRepositoryInterface
     */
    protected $mvtRepo;

    /**
     * @var OperationRepositoryInterface
     */
    protected $opRepo;

    /**
     * @var StockItemRepositoryInterface
     */
    protected $stockItemRepo;

    /**
     * @var MovementServiceInterface
     */
    protected $mvtService;

    /**
     * @var OperationServiceInterface
     */
    protected $opService;

    /**
     * @var UomServiceInterface
     */
    protected $uomService;

    /**
     * @var StockItemQueriesInterface
     */
    protected $stockItemQueries;

    /**
     * @var StockUnitFactoryInterface
     */
    protected $stockunitFactory;

    /**
     * @var MovementFactoryInterface
     */
    protected $movementFactory;

    /**
     * @var OperationFactoryInterface
     */
    protected $opFactory;

    public function setUp()
    {
        $this->initReposAndServices();
        $this->initUoms();
        $this->initWarehouseLocations();
        $this->initStockItem();
        $this->initSrcStockUnits();
    }

    protected function initUoms()
    {
        $this->uomTypeMass = new UomType('Mass');
        $this->uomT = new Uom($this->uomTypeMass, 'T', 0.001);
        $this->uomKg = new Uom($this->uomTypeMass, 'Kg', 1);
        $this->uomGr = new Uom($this->uomTypeMass, 'g', 1000);
        $this->uomMg = new Uom($this->uomTypeMass, 'mg', 1000000);
    }

    protected function initWarehouseLocations()
    {
        $this->wh = new Warehouse('Entrepôt #1', 'WH-1');
        $this->whLocSrc = new Location('Etagère #1', 'LOC-ET-1');
        $this->whLocDest = new Location('Etagère #2', 'LOC-ET-2');
        $this->wh->addLocation($this->whLocSrc);
        $this->wh->addLocation($this->whLocDest);
    }

    protected function initStockItem()
    {
        $this->stockItem = new StockItem('Tomates Vrac', $this->uomKg);
        $this->stockItem->setOutputStrategy(new OutputStrategy('default', []));
    }

    protected function initSrcStockUnits()
    {
        $itemUom = $this->stockItem->getUom();
        $q1 = $this->stockUnitFactory->createNew($this->stockItem,
            new UomQty($itemUom, 5), $this->whLocSrc);
        $q2 = $this->stockUnitFactory->createNew($this->stockItem,
            new UomQty($itemUom, 3), $this->whLocSrc);
        $q3 = $this->stockUnitFactory->createNew($this->stockItem,
            new UomQty($itemUom, 10), $this->whLocSrc);

        $this->unitRepo->addAll([$q1, $q2, $q3]);
    }

    protected function initReposAndServices()
    {
        $this->mvtRepo = new MovementRepository();
        $this->unitRepo = new StockUnitRepository();
        $this->opRepo = new OperationRepository();
        $this->stockItemRepo = new StockItemRepository();

        $this->movementFactory = new MovementFactory(new MovementCodeGenerator());
        $this->stockUnitFactory = new StockUnitFactory(new StockUnitCodeGenerator());
        $this->opFactory = new OperationFactory(new OperationCodeGenerator());

        $this->mvtService = new MovementService($this->mvtRepo, $this->unitRepo,
            $this->movementFactory, $this->stockUnitFactory);
        $this->opService = new OperationService($this->opRepo,
            $this->mvtService, $this->opFactory);
        $this->uomService = new UomService($this->unitRepo);

        $this->stockItemQueries = new StockItemQueries($this->unitRepo);
    }
}
