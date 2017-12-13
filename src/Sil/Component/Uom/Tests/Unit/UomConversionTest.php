<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Uom\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sil\Component\Uom\Model\Uom;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class UomConversionTest extends TestCase
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
    public function testUomConversion()
    {
        $uomMg = $this->getUomByName('mg');
        $uomGr = $this->getUomByName('g');
        $uomKg = $this->getUomByName('Kg');
        $uomT = $this->getUomByName('T');

        $valueInKg = $uomGr->convertValueTo(20.50, $uomKg);
        $valueInT = $uomKg->convertValueTo($valueInKg, $uomT);
        $valueInMg = $uomT->convertValueTo($valueInT, $uomMg);

        $this->assertEquals($valueInKg, 0.02050);
        $this->assertEquals($valueInT, 0.00002050);
        $this->assertEquals($valueInMg, 20500);
    }

    /**
     * Uoms should not be convertible into another Uom of different type.
     */
    public function testUomConversionBetweenDifferentUomType()
    {
        $uomKg = $this->getUomByName('Kg');
        $uomMeter = $this->getUomByName('m');

        $this->expectException(\InvalidArgumentException::class);
        $uomMeter->convertValueTo(15.00, $uomKg);
    }
}
