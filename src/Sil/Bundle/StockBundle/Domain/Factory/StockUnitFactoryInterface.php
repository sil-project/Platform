<?php

declare(strict_types=1);

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Domain\Factory;

use Sil\Bundle\StockBundle\Domain\Entity\StockUnit;
use Sil\Bundle\StockBundle\Domain\Entity\StockItemInterface;
use Sil\Bundle\StockBundle\Domain\Entity\UomQty;
use Sil\Bundle\StockBundle\Domain\Entity\Location;
use Sil\Bundle\StockBundle\Domain\Entity\BatchInterface;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
interface StockUnitFactoryInterface
{
    /**
     * @param StockItemInterface  $item
     * @param UomQty              $qty
     * @param Location            $location
     * @param BatchInterface|null $batch
     *
     * @return StockUnit
     */
    public function createNew(StockItemInterface $item, UomQty $qty, Location $location,
        ?BatchInterface $batch = null): StockUnit;

    /**
     * @param StockUnit $srcUnit
     * @param Location  $location
     *
     * @return StockUnit
     */
    public function createFrom(StockUnit $srcUnit, Location $location): StockUnit;
}
