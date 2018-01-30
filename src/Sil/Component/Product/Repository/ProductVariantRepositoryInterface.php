<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Product\Repository;

use Blast\Component\Resource\Repository\ResourceRepositoryInterface;
use Doctrine\Common\Collections\Collection;
use Sil\Component\Product\Model\ProductInterface;

interface ProductVariantRepositoryInterface extends ResourceRepositoryInterface
{
    /**
     * find all variants for a given product.
     *
     * @param ProductInterface $product Current product
     *
     * @return Collection A collection of product variant
     */
    public function getVariantsForProduct(ProductInterface $product): Collection;

    /**
     * find all active variants for a given product.
     *
     * @param ProductInterface $product Current product
     *
     * @return Collection A collection of product variant
     */
    public function getActiveVariantsForProduct(ProductInterface $product): Collection;

    /**
     * find all inactive variants for a given product.
     *
     * @param ProductInterface $product Current product
     *
     * @return Collection A collection of product variant
     */
    public function getInactiveVariantsForProduct(ProductInterface $product): Collection;
}
