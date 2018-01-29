<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Stock\Factory;

use Sil\Component\Stock\Model\Movement;
use Sil\Component\Stock\Model\StockItemInterface;
use Sil\Component\Uom\Model\UomQty;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
interface MovementFactoryInterface
{
    /**
     * @return Movement
     */
    public function createDraft(StockItemInterface $stockItem, UomQty $qty): Movement;
}
