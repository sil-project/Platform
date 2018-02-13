<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Product\Factory;

use Sil\Component\Product\Generator\ProductCodeGenerator;
use Sil\Component\Product\Generator\ProductVariantCodeGenerator;
use Blast\Component\Code\Model\CodeInterface;
use Sil\Component\Product\Model\ProductInterface;
use Sil\Component\Product\Model\Option;

class CodeFactory
{
    /**
     * Generate product code.
     *
     * @param string $productName Current Product name
     *
     * @return CodeInterface Generated Code
     */
    public static function generateProductCode(string $productName): CodeInterface
    {
        $productCodeGenerator = new ProductCodeGenerator();

        return $productCodeGenerator->generate($productName);
    }

    /**
     * Generate product variant code.
     *
     * @param ProductInterface $product        Current Product
     * @param array|Option[]   $productOptions array of Options
     *
     * @return CodeInterface Generated Code
     */
    public static function generateProductVariantCode(ProductInterface $product, array $productOptions): CodeInterface
    {
        $productCodeGenerator = new ProductVariantCodeGenerator();

        return $productCodeGenerator->generate($product->getCode(), $productOptions);
    }
}
