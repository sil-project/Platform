<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Tests\Unit\InMemoryRepository;

use Sil\Bundle\StockBundle\Domain\Repository\LocationRepositoryInterface;
use Sil\Bundle\StockBundle\Domain\Entity\Location;
use Sil\Bundle\StockBundle\Domain\Entity\StockItemInterface;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class LocationRepository extends InMemoryRepository implements LocationRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Location::class);
    }

    public function findInternalLocations(): array
    {
    }

    public function findCustomerLocations(): array
    {
    }

    public function findSupplierLocations(): array
    {
    }

    public function findByOwnedItem(StockItemInterface $item, ?string $locationType = null): array
    {
    }
}
