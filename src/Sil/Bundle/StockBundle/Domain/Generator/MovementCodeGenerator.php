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

namespace Sil\Bundle\StockBundle\Domain\Generator;

use Sil\Bundle\StockBundle\Domain\Entity\StockItemInterface;
use Sil\Bundle\StockBundle\Domain\Entity\UomQty;
use Sil\Bundle\StockBundle\Domain\Entity\Location;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class MovementCodeGenerator implements MovementCodeGeneratorInterface
{
    /**
     * @param StockItemInterface $stockItem
     * @param UomQty             $qty
     * @param Location           $srcLocation
     * @param Location           $destLocation
     *
     * @return string
     */
    public function generate(StockItemInterface $stockItem, UomQty $qty): string
    {
        return strtoupper(substr(md5((string) mt_rand()), 0, 7));
    }
}
