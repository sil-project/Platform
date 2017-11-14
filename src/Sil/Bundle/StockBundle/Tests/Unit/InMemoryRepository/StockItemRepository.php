<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Test\Unit\InMemoryRepository;

use Sil\Bundle\StockBundle\Domain\Repository\StockItemRepositoryInterface;
use Sil\Bundle\StockBundle\Domain\Entity\StockItem;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class StockItemRepository extends InMemoryRepository implements StockItemRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(StockItem::class);
    }
}
