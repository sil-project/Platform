<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Test\Unit;

use Sil\Bundle\StockBundle\Domain\Entity\UomQty;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class MovementTest extends AbstractStockTestCase
{
    public function testMovementLifecycle()
    {
        $uomQty = new UomQty($this->uomGr, 6500);

        $mvt = $this->mvtService->createDraft($this->stockItem, $uomQty,
            $this->whLocSrc, $this->whLocDest);

        $this->assertTrue($mvt->isDraft());

        $this->mvtService->confirm($mvt);
        $this->assertTrue($mvt->isConfirmed());

        $this->mvtService->reserveUnits($mvt);
        $this->assertTrue($mvt->isFullyReserved() && $mvt->isAvailable());

        $this->mvtService->apply($mvt);
        $this->assertTrue($mvt->isDone());

        $srcLocQty = $this->stockItemQueries
            ->getQtyByLocation($this->stockItem, $this->whLocSrc);

        $destLocQty = $this->stockItemQueries
            ->getQtyByLocation($this->stockItem, $this->whLocDest);

        $itemQty = $this->stockItemQueries->getQty($this->stockItem);

        $this->assertTrue($itemQty->getValue() == 18);
        $this->assertTrue($srcLocQty->getValue() == 11.5);
        $this->assertTrue($destLocQty->getValue() == 6.5);

        $this->expectException(\DomainException::class);

        $this->mvtService->cancel($mvt);
    }
}
