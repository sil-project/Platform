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

use Doctrine\Common\Collections\Collection;
use Sil\Component\Product\Model\ProductInterface;
use Blast\Component\Resource\Repository\InMemoryRepository;
use Sil\Component\Product\Repository\ProductVariantRepositoryInterface;

class ProductVariantRepository extends InMemoryRepository implements ProductVariantRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getVariantsForProduct(ProductInterface $product): Collection
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveVariantsForProduct(ProductInterface $product): Collection
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getInactiveVariantsForProduct(ProductInterface $product): Collection
    {
    }
}
