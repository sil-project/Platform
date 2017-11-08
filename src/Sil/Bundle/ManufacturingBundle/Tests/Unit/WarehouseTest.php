<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Test\Unit;

use PHPUnit\Framework\TestCase;
use Sil\Bundle\StockBundle\Domain\Entity;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class WarehouseTest extends TestCase
{
    public function testAddingLocation()
    {
        $wh = new Entity\Warehouse('Entrepôt #1', 'WH-1');
        $loc = new Entity\Location('Etagère #1', 'LOC-ET-1');
        $wh->addLocation($loc);

        $this->assertTrue($wh->hasLocation($loc));

        $wh->removeLocation($loc);
        $this->assertFalse($wh->hasLocation($loc));
    }
}
