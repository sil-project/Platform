<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Services;

use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Provider\ProductVariantsPricesProviderInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;

class ProductVariantsPricesProvider implements ProductVariantsPricesProviderInterface
{
    /**
     * @var ProductVariantPriceCalculatorInterface
     */
    private $productVariantPriceCalculator;

    /**
     * @param ProductVariantPriceCalculatorInterface $productVariantPriceCalculator
     */
    public function __construct(ProductVariantPriceCalculatorInterface $productVariantPriceCalculator)
    {
        $this->productVariantPriceCalculator = $productVariantPriceCalculator;
    }

    /**
     * {@inheritdoc}
     */
    public function provideVariantsPrices(ProductInterface $product, ChannelInterface $channel): array
    {
        $variantsPrices = [];

        /** @var ProductVariantInterface $variant */
        foreach ($product->getVariants() as $variant) {
            $variantsPrices[] = $this->constructOptionsMap($variant, $channel);
        }

        return $variantsPrices;
    }

    /**
     * @param ProductVariantInterface $variant
     * @param ChannelInterface        $channel
     *
     * @return array
     */
    private function constructOptionsMap(ProductVariantInterface $variant, ChannelInterface $channel): array
    {
        $optionMap = [];

        /** @var ProductOptionValueInterface $option */
        foreach ($variant->getOptionValues() as $option) {
            $optionMap[$option->getOptionCode()] = $option->getCode();
        }

        $optionMap['value'] = $this->productVariantPriceCalculator->calculate($variant, ['channel' => $channel]);
        $optionMap['enabled'] = $variant->isEnabled();

        return $optionMap;
    }
}
