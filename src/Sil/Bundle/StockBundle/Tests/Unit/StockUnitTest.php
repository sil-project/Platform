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
class StockUnitTest extends AbstractStockTestCase
{
    public function testLocationQty()
    {
        $stockItem = $this->getStockItem();
        $srcLoc = $this->getSrcLocation();
        $destLoc = $this->getDestLocation();

        $srcLocQty = $this->stockItemQueries->getQtyByLocation($stockItem, $srcLoc);
        $destLocQty = $this->stockItemQueries->getQtyByLocation($stockItem, $destLoc);

        $this->assertTrue($srcLocQty->getValue() == 18);
        $this->assertTrue($destLocQty->isZero());
    }
}
