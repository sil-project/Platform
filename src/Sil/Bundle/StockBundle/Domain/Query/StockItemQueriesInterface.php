<?php

declare(strict_types=1);

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Domain\Query;

use Sil\Bundle\StockBundle\Domain\Entity\StockItemInterface;
use Sil\Bundle\StockBundle\Domain\Entity\UomQty;
use Sil\Bundle\StockBundle\Domain\Entity\Location;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
interface StockItemQueriesInterface
{
    /**
     * @param StockItemInterface $item
     *
     * @return UomQty
     */
    public function getQty(StockItemInterface $item): UomQty;

    /**
     * @param StockItemInterface $item
     *
     * @return UomQty
     */
    public function getReservedQty(StockItemInterface $item): UomQty;

    /**
     * @param StockItemInterface $item
     * @param Location           $location
     *
     * @return UomQty
     */
    public function getQtyByLocation(StockItemInterface $item,
        Location $location): UomQty;
}
