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
class StockItemTest extends AbstractStockTestCase
{
    /**
     * A StockItem might be contained in several locations.
     */
    public function testHasLocations()
    {
    }

    /**
     * Units of a StockItem should be updated when the Uom is changed.
     */
    public function testUpdateUnitsUom()
    {
        $stockItem = $this->getStockItem();
        $uomGr = $this->getGrUom();
        $qty = $this->stockItemQueries->getQty($stockItem);
        $this->assertEquals($qty->getValue(), 18);

        $this->uomService->updateUomForStockItem($stockItem, $uomGr);

        $convertedQty = $this->stockItemQueries->getQty($stockItem);

        $this->assertTrue($convertedQty->getValue() == 18000);

        $this->assertTrue($stockItem->getUom() == $uomGr);
    }
}
