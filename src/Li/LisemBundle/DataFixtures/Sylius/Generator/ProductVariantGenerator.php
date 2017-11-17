<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\DataFixtures\Sylius\Generator;

use Doctrine\Common\Collections\ArrayCollection;
use Sil\Bundle\SeedBatchBundle\Entity\SeedBatchInterface;
use Sylius\Component\Product\Checker\ProductVariantsParityCheckerInterface;
use Sylius\Component\Product\Factory\ProductVariantFactoryInterface;
use Sylius\Component\Product\Generator\CartesianSetBuilder;
use Sylius\Component\Product\Generator\ProductVariantGeneratorInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Webmozart\Assert\Assert;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
final class ProductVariantGenerator implements ProductVariantGeneratorInterface
{
    /**
     * @var ProductVariantFactoryInterface
     */
    private $productVariantFactory;

    /**
     * @var CartesianSetBuilder
     */
    private $setBuilder;

    /**
     * @var ProductVariantsParityCheckerInterface
     */
    private $variantsParityChecker;

    /**
     * @param ProductVariantFactoryInterface        $productVariantFactory
     * @param ProductVariantsParityCheckerInterface $variantsParityChecker
     */
    public function __construct(
        ProductVariantFactoryInterface $productVariantFactory,
        ProductVariantsParityCheckerInterface $variantsParityChecker
    ) {
        $this->productVariantFactory = $productVariantFactory;
        $this->setBuilder = new CartesianSetBuilder();
        $this->variantsParityChecker = $variantsParityChecker;
    }

    /**
     * @param ProductInterface $product
     * @param ArrayCollection  $seedBatches
     */
    public function generate(ProductInterface $product, $seedBatches = null): void
    {
        Assert::true($product->hasOptions(), 'Cannot generate variants for an object without options.');

        if ($variety = $product->getVariety()) {
            $varietySeedBatches = $variety->getSeedBatches();
            if ($seedBatches === null) {
                $seedBatches = $varietySeedBatches;
            } else {
                $seedBatches = $seedBatches->filter(
                    function ($sb) use ($varietySeedBatches) {
                        return $varietySeedBatches->contains($sb);
                    }
                );
            }
            Assert::notEq(0, count($seedBatches), 'Cannot generate variants for a seeds product withouy seed batches');
        }

        $optionSet = [];
        $optionMap = [];

        foreach ($product->getOptions() as $key => $option) {
            foreach ($option->getValues() as $value) {
                $optionSet[$key][] = $value->getId();
                $optionMap[$value->getId()] = $value;
            }
        }
        if ($seedBatches) {
            $seedBatchSet = [];
            foreach ($seedBatches as $seedBatch) {
                $seedBatchSet[] = $seedBatch->getId();
                $optionMap[$seedBatch->getId()] = $seedBatch;
            }
            $optionSet[] = $seedBatchSet;
        }

        $permutations = $this->setBuilder->build($optionSet);

        foreach ($permutations as $permutation) {
            $variant = $this->createVariant($product, $optionMap, $permutation);

            if (!$this->variantsParityChecker->checkParity($variant, $product)) {
                $product->addVariant($variant);
            }
        }
    }

    /**
     * @param ProductInterface $product
     * @param array            $optionMap
     * @param mixed            $permutation
     *
     * @return ProductVariantInterface
     */
    private function createVariant(ProductInterface $product, array $optionMap, $permutation)
    {
        /** @var ProductVariantInterface $variant */
        $variant = $this->productVariantFactory->createForProduct($product);
        $this->addOptionValue($variant, $optionMap, $permutation);

        return $variant;
    }

    /**
     * @param ProductVariantInterface $variant
     * @param array                   $optionMap
     * @param mixed                   $permutation
     */
    private function addOptionValue(ProductVariantInterface $variant, array $optionMap, $permutation)
    {
        if (!is_array($permutation)) {
            $variant->addOptionValue($optionMap[$permutation]);

            return;
        }

        foreach ($permutation as $id) {
            if ($optionMap[$id] instanceof SeedBatchInterface) {
                $variant->addSeedBatch($optionMap[$id]);
            } else {
                $variant->addOptionValue($optionMap[$id]);
            }
        }
    }
}
