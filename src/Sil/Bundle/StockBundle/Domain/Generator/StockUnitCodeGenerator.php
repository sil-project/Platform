<?php

declare(strict_types=1);

/*
 * This file is part of the Sil Project.
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
use Sil\Bundle\StockBundle\Domain\Entity\BatchInterface;

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
