<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Domain\Repository;

use Sil\Bundle\StockBundle\Domain\Entity\StockItemInterface;
use Sil\Bundle\StockBundle\Domain\Entity\Location;
use Sil\Bundle\StockBundle\Domain\Entity\Movement;
use Sil\Bundle\StockBundle\Domain\Entity\BatchInterface;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
interface StockUnitRepositoryInterface
{
    /**
     * @param StockItemInterface $item
     *
     * @return array|StockUnit[]
     */
    public function findByStockItem(StockItemInterface $item,
        ?BatchInterface $batch = null): array;

    /**
     * @param Location $location
     * @param array    $orderBy
     *
     * @return array
     */
    public function findByLocation(Location $location, array $orderBy = [], ?int $limit = null): array;

    /**
     * @param StockItemInterface $item
     * @param Location           $location
     *
     * @return array|StockUnit[]
     */
    public function findByStockItemAndLocation(StockItemInterface $item,
        Location $location, ?BatchInterface $batch = null): array;

    /**
     * @param Movement $mvt
     *
     * @return array|StockUnit[]
     */
    public function findAvailableForMovementReservation(Movement $mvt): array;

    /**
     * @param StockItemInterface $item
     * @param array              $orderBy
     *
     * @return array|StockUnit[]
     */
    public function findAvailableByStockItem(StockItemInterface $item,
        ?BatchInterface $batch = null, array $orderBy = []): array;

    /**
     * @param StockItemInterface $item
     *
     * @return array|StockUnit[]
     */
    public function findReservedByStockItem(StockItemInterface $item,
        ?BatchInterface $batch = null): array;

    /**
     * @param Movement $mvt
     *
     * @return array|StockUnit[]
     */
    public function findReservedByMovement(Movement $mvt): array;
}
