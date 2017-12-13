<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Tests\Unit;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class MovementTest extends AbstractStockTestCase
{
    /**
     * @test
     */
    public function newly_created_movement_should_be_in_draft_mode()
    {
        $mvt = $this->createMovement();

        $this->assertTrue($mvt->isDraft(), 'a newly created Movement should be in DRAFT state');

        return $mvt;
    }

    /**
     * @test
     */
    public function movement_should_be_confirmed_before_reservation()
    {
        $mvt = $this->createMovement();

        $this->expectException(\DomainException::class);
        $this->mvtService->reserveUnits($mvt);

        $this->mvtService->confirm($mvt);
        $this->assertTrue($mvt->isConfirmed(), 'Movement should be in CONFIRMED state');

        return $mvt;
    }

    /**
     * @test
     */
    public function reserved_movement_should_be_fully_confirmed_and_available()
    {
        $mvt = $this->createMovement();
        $this->mvtService->confirm($mvt);

        $this->mvtService->reserveUnits($mvt);
        $stockItem = $this->getStockItem();

        $reservedQty = $this->stockItemQueries->getReservedQty($stockItem);

        $this->assertTrue($mvt->isFullyReserved(), 'should be fully reserved - reserved qty');
        $this->assertTrue($mvt->isAvailable(), 'should be in AVAILABLE state');

        return $mvt;
    }

    /**
     * @test
     */
    public function applied_movement_should_be_done()
    {
        $mvt = $this->createMovement();
        $this->mvtService->confirm($mvt);
        $this->mvtService->reserveUnits($mvt);
        $this->mvtService->apply($mvt);
        $this->assertTrue($mvt->isDone(), 'should be in DONE state');

        $stockItem = $this->getStockItem();

        $srcLocQty = $this->stockItemQueries
            ->getQtyByLocation($stockItem, $this->getSrcLocation());

        $destLocQty = $this->stockItemQueries
            ->getQtyByLocation($stockItem, $this->getDestLocation());

        $itemQty = $this->stockItemQueries->getQty($stockItem);

        $this->assertEquals($itemQty->getValue(), 18);
        $this->assertEquals($srcLocQty->getValue(), 16.5);
        $this->assertEquals($destLocQty->getValue(), 1.5);

        $this->expectException(\DomainException::class);
        $this->mvtService->cancel($mvt);
    }
}
