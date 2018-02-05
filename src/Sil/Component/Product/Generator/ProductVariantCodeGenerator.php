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

use Sil\Component\Product\Model\ProductInterface;
use Sil\Component\Product\Model\CodeInterface;
use Sil\Component\Product\Model\ProductVariantCode;

class ProductVariantCodeGenerator extends AbstractCodeGenerator implements ProductVariantCodeGeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function generate(ProductInterface $product, array $options): CodeInterface
    {
        $optionsCodePart = '';

        $firstIteration = true;

        foreach ($options as $option) {
            if (!$firstIteration) {
                $optionsCodePart .= '-';
            } else {
                $firstIteration = false;
            }

            $optionsCodePart .= strtoupper($option->getValue());
        }

        return new ProductVariantCode(sprintf(
            '%s-%s',
            $product->getCode()->getValue(),
            $optionsCodePart
        ));
    }
}
