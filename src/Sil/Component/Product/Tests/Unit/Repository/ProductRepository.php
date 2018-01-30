<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Product\Tests\Unit\Repository;

use Blast\Component\Resource\Repository\InMemoryRepository;
use Doctrine\Common\Collections\Collection;
use Sil\Component\Product\Model\ProductInterface;
use Sil\Component\Product\Repository\ProductRepositoryInterface;

class ProductRepository extends InMemoryRepository implements ProductRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findProductByCode(string $productCode): ?ProductInterface
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledProducts(): Collection
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getDisabledProducts(): Collection
    {
    }
}
