<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Domain\Repository\InMemory;

use Sil\Bundle\StockBundle\Domain\Repository\MovementRepositoryInterface;
use Sil\Bundle\StockBundle\Domain\Entity\Movement;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class MovementRepository extends InMemoryRepository implements MovementRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Movement::class);
    }
}
