<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Uom\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sil\Component\Uom\Model\Uom;
use Sil\Component\Uom\Model\UomQty;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 *
 * @coversDefaultClass \Sil\Component\Uom\Model\UomQty
 */
class UomQtyTest extends TestCase
{
    use Fixture\UomFixturesTrait;

    public function setUp()
    {
        $this->loadMassUomFixtures();
        $this->loadLengthUomFixtures();
    }

    /**
     * Uoms should be convertible into other Uom of the same type.
     */
    public function testQtyConversion()
    {
        $uomMg = $this->getUomByName('mg');
        $uomGr = $this->getUomByName('g');
        $uomKg = $this->getUomByName('Kg');
        $uomT = $this->getUomByName('T');

        $qtyInGr = new UomQty($uomGr, 20.50);
        $qtyInKg = $qtyInGr->convertTo($uomKg);
        $qtyInT = $qtyInGr->convertTo($uomT);
        $qtyInMg = $qtyInT->convertTo($uomMg);

        $this->assertEquals($qtyInKg->getValue(), 0.02050);
        $this->assertEquals($qtyInT->getValue(), 0.00002050);
        $this->assertEquals($qtyInMg->getValue(), 20500);
    }

    /**
     * Qty should be additionnable while remaining read-only.
     *
     * @covers ::increasedBy
     */
    public function testQtyIncrease()
    {
        $uomGr = $this->getUomByName('g');
        $uomKg = $this->getUomByName('Kg');

        $qtyInGr = new UomQty($uomGr, 800);
        $qtyInKg = new UomQty($uomKg, 10);
        $newQty = $qtyInGr->increasedBy($qtyInKg);

        $this->assertEquals($newQty->getUom(), $uomGr, 'resulting qty should be of the same uom of the initial qty');
        $this->assertEquals($newQty->getValue(), 10800);
    }

    /**
     * Qty should be additionnable while remaining read-only.
     *
     * @covers ::decreasedBy
     */
    public function testQtyDecrease()
    {
        $uomGr = $this->getUomByName('g');
        $uomKg = $this->getUomByName('Kg');

        $qtyInGr = new UomQty($uomGr, 500);
        $qtyInKg = new UomQty($uomKg, 1);
        $newQty = $qtyInKg->decreasedBy($qtyInGr);

        $this->assertEquals($newQty->getUom(), $uomKg, 'resulting qty should be of the same uom of the initial qty');
        $this->assertEquals($newQty->getValue(), 0.5);
    }

    /**
     * Qty should be multipliable while remaining read-only.
     *
     * @covers ::multipliedBy
     */
    public function testQtyMultiply()
    {
        $uomGr = $this->getUomByName('g');
        $uomKg = $this->getUomByName('Kg');

        $qtyInGr = new UomQty($uomGr, 800);
        $newQty = $qtyInGr->multipliedBy(2);

        $this->assertEquals($newQty->getUom(), $uomGr, 'resulting qty should be of the same uom of the initial qty');
        $this->assertEquals($newQty->getValue(), 1600);
    }

    /**
     * Qty should be divisible while remaining read-only.
     *
     * @covers ::dividedBy
     */
    public function testQtyDivide()
    {
        $uomGr = $this->getUomByName('g');
        $uomKg = $this->getUomByName('Kg');

        $qtyInGr = new UomQty($uomGr, 800);
        $newQty = $qtyInGr->dividedBy(2);

        $this->assertEquals($newQty->getUom(), $uomGr, 'resulting qty should be of the same uom of the initial qty');
        $this->assertEquals($newQty->getValue(), 400);
    }

    /**
     * Qty should be divisible while remaining read-only.
     *
     * @covers ::isEqualTo
     * @covers ::isGreaterOrEqualTo
     * @covers ::isSmallerOrEqualTo
     */
    public function testQtyEquality()
    {
        $uomGr = $this->getUomByName('g');
        $uomKg = $this->getUomByName('Kg');
        $uomMeter = $this->getUomByName('m');

        $qtyInGr = new UomQty($uomGr, 800);
        $qtyInKg = new UomQty($uomKg, 0.800);
        $otherQtyInGr = new UomQty($uomGr, 500);
        $otherQtyInMeter = new UomQty($uomMeter, 800);

        $this->assertTrue($qtyInGr->isEqualTo($qtyInKg), 'Same Qties should be equal even if the Uom is different');
        $this->assertTrue($qtyInGr->isGreaterOrEqualTo($qtyInKg));
        $this->assertTrue($qtyInGr->isSmallerOrEqualTo($qtyInKg));

        $this->assertFalse($qtyInGr->isEqualTo($otherQtyInGr));

        $this->expectException(\InvalidArgumentException::class);
        $qtyInGr->isEqualTo($otherQtyInMeter);
    }

    /**
     * Qty should be comaparable.
     *
     * @covers ::isGreaterThan
     * @covers ::isGreaterOrEqualTo
     * @covers ::isSmallerThan
     * @covers ::isSmallerOrEqualTo
     */
    public function testQtyComparison()
    {
        $uomGr = $this->getUomByName('g');
        $uomKg = $this->getUomByName('Kg');
        $uomMeter = $this->getUomByName('m');

        $qtyInGr = new UomQty($uomGr, 800);
        $qtyInKg = new UomQty($uomKg, 0.900);

        $this->assertTrue($qtyInKg->isGreaterThan($qtyInGr));
        $this->assertTrue($qtyInKg->isGreaterOrEqualTo($qtyInGr));

        $this->assertTrue($qtyInGr->isSmallerThan($qtyInKg));
        $this->assertTrue($qtyInGr->isSmallerOrEqualTo($qtyInKg));
    }

    /**
     * Qty should be comapared to zero.
     *
     * @covers ::isZero
     */
    public function testZeroQty()
    {
        $uomGr = $this->getUomByName('g');
        $uomKg = $this->getUomByName('Kg');
        $uomMeter = $this->getUomByName('m');

        $qtyInGr = new UomQty($uomGr, 800);
        $qtyInKg = new UomQty($uomKg, 0);

        $this->assertTrue($qtyInKg->isZero());
        $this->assertFalse($qtyInGr->isZero());
    }
}
