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

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class WarehouseTest extends TestCase
{
    public function testAddingLocation()
    {
        $wh = new Model\Warehouse('Entrepôt #1', 'WH-1');
        $loc = new Model\Location('Etagère #1', 'LOC-ET-1');
        $wh->addLocation($loc);

        $this->assertTrue($wh->hasLocation($loc));

        $wh->removeLocation($loc);
        $this->assertFalse($wh->hasLocation($loc));
    }
}
