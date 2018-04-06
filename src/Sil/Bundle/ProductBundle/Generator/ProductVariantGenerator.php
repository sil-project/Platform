<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ProductBundle\Generator;

use Sil\Component\Product\Model\ProductInterface;
use Sil\Component\Product\Service\ProductService;
use function BenTools\CartesianProduct\cartesian_product;

class ProductVariantGenerator extends ProductService
{
    /**
     * @var ProductVariantCodeGenerator
     */
    protected $productVariantCodeGenerator;

    public function generateVariantsForProduct(ProductInterface $product): void
    {
        $options = cartesian_product($this->getProductOptionsAsArray($product))->asArray();

        if (count($options) > 0) {
            // Add a variant for each unique combination of options

            foreach ($options as $option) {
                $variantName = $product->getName();
                $variantCode = $this->productVariantCodeGenerator->generate($product->getCode(), $option);

                $variant = new $this->productVariantClass($product, $variantCode, $variantName);

                foreach ($option as $optionType => $optionValue) {
                    $variantName .= ' ' . $optionValue->getValue();

                    $variant->addOption($optionValue);
                }

                $variant->setName($variantName);
            }
        } else {
            // Add a default product variant if no option has been set

            $variantName = $product->getName();
            $variantCode = $this->productVariantCodeGenerator->generate($product->getCode(), []);

            $variant = new $this->productVariantClass($product, $variantCode, $variantName);
            $variant->setName($variantName);
        }
    }

    /**
     * @param ProductVariantCodeGenerator $productVariantCodeGenerator
     */
    public function setProductVariantCodeGenerator(ProductVariantCodegenerator $productVariantCodeGenerator): void
    {
        $this->productVariantCodeGenerator = $productVariantCodeGenerator;
    }
}
