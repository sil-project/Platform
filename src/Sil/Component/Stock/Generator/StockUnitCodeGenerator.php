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
use Sil\Component\Stock\Model\BatchInterface;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class StockUnitCodeGenerator implements StockUnitCodeGeneratorInterface
{
    /**
     * @param StockItemInterface  $item
     * @param UomQty              $qty
     * @param Location            $location
     * @param BatchInterface|null $batch
     *
     * @return string
     */
    public function generate(StockItemInterface $item, UomQty $qty, Location $location,
        ?BatchInterface $batch = null): string
    {
        return strtoupper(substr(md5((string) mt_rand()), 0, 7));
    }
}
