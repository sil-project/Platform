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

use Sil\Bundle\StockBundle\Domain\Entity\UomType;
use Sil\Bundle\StockBundle\Domain\Entity\Uom;
use Sil\Bundle\StockBundle\Domain\Entity\UomQty;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class StockUomTest extends AbstractStockTestCase
{
    /**
     * Uoms should be convertible into other Uom of the same type.
     */
    public function testUomConversion()
    {
        $qtyInGr = new UomQty($this->uomGr, 20.50);
        $qtyInKg = $qtyInGr->convertTo($this->uomKg);
        $qtyInT = $qtyInGr->convertTo($this->uomT);
        $qtyInMg = $qtyInT->convertTo($this->uomMg);

        $this->assertTrue($qtyInKg->getValue() == 0.02050);
        $this->assertTrue($qtyInT->getValue() == 0.00002050);
        $this->assertTrue($qtyInMg->getValue() == 20500);
    }

    /**
     * Uoms should not be convertible into other Uom of different type.
     */
    public function testUomConversionBetweenDifferentUomType()
    {
        $plopType = new UomType('Plop');
        $plopUom = new Uom($plopType, 'g', 0.001);
        $qtyInPlop = new UomQty($plopUom, 20.50);

        $this->expectException(\InvalidArgumentException::class);
        $qtyInPlop->convertTo($this->uomKg);
    }
}
