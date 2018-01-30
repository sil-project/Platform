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

interface ProductRepositoryInterface extends ResourceRepositoryInterface
{
    /**
     * find a product for a given code.
     *
     * @param string $productCode The unique code for a product
     *
     * @return ProductInterface A matching product or NULL if not found
     */
    public function findProductByCode(string $productCode): ?ProductInterface;

    /**
     * find all enabled products.
     *
     * @return Collection All enabled products found
     */
    public function getEnabledProducts(): Collection;

    /**
     * find all disabled products.
     *
     * @return Collection All disabled products found
     */
    public function getDisabledProducts(): Collection;
}
