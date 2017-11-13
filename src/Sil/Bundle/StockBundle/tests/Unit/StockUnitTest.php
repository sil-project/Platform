<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Test\Unit;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class StockUnitTest extends AbstractStockTestCase
{
    public function testLocationQty()
    {
        $srcLocQty = $this->stockItemQueries
            ->getQtyByLocation($this->stockItem, $this->whLocSrc);

        $destLocQty = $this->stockItemQueries
            ->getQtyByLocation($this->stockItem, $this->whLocDest);

        $this->assertTrue($srcLocQty->getValue() == 18);
        $this->assertTrue($destLocQty->isZero());
    }
}
