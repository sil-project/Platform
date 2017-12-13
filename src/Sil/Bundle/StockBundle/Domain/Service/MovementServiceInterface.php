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

namespace Sil\Bundle\StockBundle\Domain\Service;

use Sil\Bundle\StockBundle\Domain\Entity\StockItemInterface;
use Sil\Bundle\StockBundle\Domain\Entity\BatchInterface;
use Sil\Component\Uom\Model\UomQty;
use Sil\Bundle\StockBundle\Domain\Entity\Location;
use Sil\Bundle\StockBundle\Domain\Entity\Movement;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
interface MovementServiceInterface
{
    /**
     * @param StockItemInterface  $item
     * @param UomQty              $qty
     * @param Location            $srcLoc
     * @param Location            $destLoc
     * @param BatchInterface|null $batch
     *
     * @return Movement
     */
    public function createDraft(StockItemInterface $item, UomQty $qty,
            Location $srcLoc, Location $destLoc, ?BatchInterface $batch = null): Movement;

    /**
     * @param Movement $mvt
     */
    public function confirm(Movement $mvt): void;

    /**
     * @param Movement $mvt
     */
    public function reserveUnits(Movement $mvt): void;

    /**
     * @param Movement $mvt
     */
    public function apply(Movement $mvt): void;

    /**
     * @param Movement $mvt
     */
    public function cancel(Movement $mvt): void;
}
