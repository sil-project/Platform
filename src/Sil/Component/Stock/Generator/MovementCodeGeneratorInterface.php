<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Stock\Generator;

use Sil\Component\Stock\Model\StockItemInterface;
use Sil\Component\Uom\Model\UomQty;
use Sil\Component\Stock\Model\Location;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
interface MovementCodeGeneratorInterface
{
    /**
     * @param StockItemInterface $stockItem
     * @param UomQty             $qty
     * @param Location           $srcLocation
     * @param Location           $destLocation
     *
     * @return string
     */
    public function generate(StockItemInterface $stockItem, UomQty $qty): string;
}
