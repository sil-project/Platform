<?php

/*
 * This file is part of the Lisem Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\Checker;

use Sylius\Component\Product\Checker\ProductVariantsParityCheckerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
final class ProductVariantsParityChecker implements ProductVariantsParityCheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function checkParity(ProductVariantInterface $variant, ProductInterface $product): bool
    {
        foreach ($product->getVariants() as $existingVariant) {
            // This check is required, because this function has to look for any other different variant with same option values set
            if (
                $variant === $existingVariant ||
                count($variant->getOptionValues()) !== count($product->getOptions()) ||
                $this->checkSeedBatchIsAlreadyUsed($existingVariant->getSeedBatches(), $variant->getSeedBatches())
            ) {
                continue;
            }

            if ($this->matchOptions($variant, $existingVariant)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ProductVariantInterface $variant
     * @param ProductVariantInterface $existingVariant
     *
     * @return bool
     */
    private function matchOptions(ProductVariantInterface $variant, ProductVariantInterface $existingVariant)
    {
        foreach ($variant->getOptionValues() as $option) {
            if (!$existingVariant->hasOptionValue($option)) {
                return false;
            }
        }

        return true;
    }

    private function checkSeedBatchIsAlreadyUsed($existingSeedBatches, $seedBatches)
    {
        $intersection = false;
        if ($existingSeedBatches !== null) {
            $existingSeedBatches->forAll(function ($seedBatch) use ($seedBatches) {
                if ($seedBatches->contains($seedBatch)) {
                    $intersection = true;
                }
            });
        }

        return $intersection;
    }
}
