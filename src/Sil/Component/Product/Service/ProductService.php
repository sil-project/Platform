<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Product\Service;

use Sil\Component\Product\Model\ProductVariant;
use Sil\Component\Product\Model\ProductInterface;
use Sil\Component\Product\Factory\CodeFactory;
use function BenTools\CartesianProduct\cartesian_product;

class ProductService
{
    /**
     * @var string
     */
    protected $productVariantClass = ProductVariant::class;

    public function generateVariantsForProduct(ProductInterface $product): void
    {
        $options = cartesian_product($this->getProductOptionsAsArray($product))->asArray();

        foreach ($options as $option) {
            $variantName = $product->getName();
            $variantCode = CodeFactory::generateProductVariantCode($product, $option);

            $variant = new $this->productVariantClass($product, $variantCode, $variantName);

            foreach ($option as $optionType => $optionValue) {
                $variantName .= ' ' . $optionValue->getValue();

                $variant->addOption($optionValue);
            }

            $variant->setName($variantName);
        }
    }

    /**
     * Retreive Option trought OptionType(s) as an associative array.
     *
     * @return array
     */
    protected function getProductOptionsAsArray(ProductInterface $product): array
    {
        $optionsAsArray = [];

        foreach ($product->getOptionTypes() as $label) {
            foreach ($label->getOptions() as $option) {
                $optionsAsArray[$label->getName()][] = $option;
            }
        }

        return $optionsAsArray;
    }

    /**
     * @param string $productVariantClass
     */
    public function setProductVariantClass(string $productVariantClass): void
    {
        $this->productVariantClass = $productVariantClass;
    }
}
