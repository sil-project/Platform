<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Product\Generator;

use Sil\Component\Product\Model\CodeInterface;
use Sil\Component\Product\Model\Option;
use Sil\Component\Product\Model\ProductInterface;

interface ProductVariantCodeGeneratorInterface extends CodeGeneratorInterface
{
    /**
     * Generate product variant code.
     *
     * @param ProductInterface $product Current Product
     * @param array|Option[]   $options array of Options
     *
     * @return CodeInterface Generated Code
     */
    public function generate(ProductInterface $product, array $options): CodeInterface;
}
