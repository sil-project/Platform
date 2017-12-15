<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Domain\Repository;

use Sil\Bundle\StockBundle\Domain\Entity\StockItemInterface;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
interface LocationRepositoryInterface
{
    public function findInternalLocations(): array;

    public function findCustomerLocations(): array;

    public function findSupplierLocations(): array;

    public function findByOwnedItem(StockItemInterface $item,
        ?string $locationType = null): array;
}
