<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Stock\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sil\Component\Stock\Model;
use Sil\Component\Stock\Repository;
use Sil\Component\Stock\Service;
use Sil\Component\Stock\Query;
use Sil\Component\Stock\Factory;
use Sil\Component\Stock\Generator;
use Sil\Component\Uom\Model\Uom;
use Sil\Component\Uom\Model\UomType;
use Sil\Component\Uom\Model\UomQty;
use Sil\Component\Uom\Tests\Unit\Fixture\UomFixturesTrait;
use Sil\Component\Stock\Model\OperationType;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class AbstractStockTestCase extends TestCase
{
    use UomFixturesTrait;

    /**
     * @var Repository\StockUnitRepositoryInterface
     */
    protected $unitRepo;

    /**
     * @var Repository\MovementRepositoryInterface
     */
    protected $mvtRepo;

    /**
     * @var Repository\OperationRepositoryInterface
     */
    protected $opRepo;

    /**
     * @var Repository\StockItemRepositoryInterface
     */
    protected $stockItemRepo;

    /**
     * @var Repository\MovementServiceInterface
     */
    protected $mvtService;

    /**
     * @var Service\OperationServiceInterface
     */
    protected $opService;

    /**
     * @var Query\StockItemQueriesInterface
     */
    protected $stockItemQueries;

    /**
     * @var Factory\StockUnitFactoryInterface
     */
    protected $stockunitFactory;

    /**
     * @var Factory\MovementFactoryInterface
     */
    protected $movementFactory;

    /**
     * @var Factory\OperationFactoryInterface
     */
    protected $opFactory;

    public function setUp()
    {
        $this->loadMassUomFixtures();
        $this->initReposAndServices();
        $this->initWarehouseLocations();
        $this->initStockItem();
        $this->initSrcStockUnits();
    }

    protected function initWarehouseLocations()
    {
        $wh = new Model\Warehouse('Entrepôt #1', 'WH-1');
        $this->createLocation($wh, 'LOC-ET-1', 'Etagère #1', Model\LocationType::internal());
        $this->createLocation($wh, 'LOC-ET-2', 'Etagère #2', Model\LocationType::internal());
    }

    protected function createLocation(Model\Warehouse $wh, string $code, string $name, Model\LocationType $type)
    {
        $loc = Model\Location::createDefault($code, $name, $type);
        $wh->addLocation($loc);
        $this->locationRepo->add($loc);

        return $loc;
    }

    protected function initStockItem()
    {
        $uomKg = $this->getKgUom();
        $stockItem = new Model\StockItem();
        $stockItem->setName('Tomates Vrac');
        $stockItem->setUom($uomKg);
        $stockItem->setOutputStrategy(new Model\OutputStrategy('default', []));
        $this->stockItemRepo->add($stockItem);
    }

    protected function initSrcStockUnits()
    {
        $stockItem = $this->getStockItem();
        $itemUom = $stockItem->getUom();
        $srcLoc = $this->getSrcLocation();
        $this->createStockUnit($stockItem, $itemUom, 5, $srcLoc);
        $this->createStockUnit($stockItem, $itemUom, 3, $srcLoc);
        $this->createStockUnit($stockItem, $itemUom, 10, $srcLoc);
    }

    protected function createStockUnit(Model\StockItemInterface $stockItem, Uom $itemUom, float $qtyValue, Model\Location $loc)
    {
        $stockUnit = $this->stockUnitFactory->createNew($stockItem, new UomQty($itemUom, $qtyValue), $loc);
        $this->unitRepo->add($stockUnit);

        return $stockUnit;
    }

    protected function initReposAndServices()
    {
        $this->mvtRepo = new InMemoryRepository\MovementRepository();
        $this->unitRepo = new InMemoryRepository\StockUnitRepository();
        $this->opRepo = new InMemoryRepository\OperationRepository();
        $this->stockItemRepo = new InMemoryRepository\StockItemRepository();
        $this->locationRepo = new InMemoryRepository\LocationRepository();

        $this->movementFactory = new Factory\MovementFactory(new Generator\MovementCodeGenerator());
        $this->stockUnitFactory = new Factory\StockUnitFactory(new Generator\StockUnitCodeGenerator());
        $this->opFactory = new Factory\OperationFactory(new Generator\OperationCodeGenerator());

        $this->mvtService = new Service\MovementService(
            $this->mvtRepo,
            $this->unitRepo,
            $this->movementFactory,
            $this->stockUnitFactory
        );
        $this->opService = new Service\OperationService(
            $this->opRepo,
            $this->mvtService,
            $this->opFactory
        );
        $this->uomService = new Service\UomService($this->unitRepo);
        $this->stockItemQueries = new Query\StockItemQueries($this->unitRepo);
    }

    protected function getStockItem()
    {
        return $this->stockItemRepo->findOneBy(['name' => 'Tomates Vrac']);
    }

    protected function getGrUom()
    {
        return $this->getUomByName('g');
    }

    protected function getKgUom()
    {
        return $this->getUomByName('Kg');
    }

    protected function getSrcLocation()
    {
        return $this->locationRepo->findOneBy(['code' => 'LOC-ET-1']);
    }

    protected function getDestLocation()
    {
        return $this->locationRepo->findOneBy(['code' => 'LOC-ET-2']);
    }

    public function getUomClass()
    {
        return Uom::class;
    }

    public function getUomTypeClass()
    {
        return UomType::class;
    }

    protected function createInternalTransferOperation()
    {
        $srcLoc = $this->getSrcLocation();
        $destLoc = $this->getDestLocation();

        return $this->opService->createDraft(OperationType::internalTransfer(), $srcLoc, $destLoc);
    }

    protected function createMovement()
    {
        $stockItem = $this->getStockItem();
        $uomGr = $this->getGrUom();
        $uomQty = new UomQty($uomGr, 1500);

        $op = $this->createInternalTransferOperation();
        $mvt = $this->mvtService->createDraft(
          $stockItem, $uomQty,
          $this->getSrcLocation(),
          $this->getDestLocation()
        );
        $mvt->setOperation($op);

        return $mvt;
    }
}
