<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ProductBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Sil\Component\Product\Model\ProductInterface;
use Sil\Component\Product\Repository\ProductRepositoryInterface;

class ProductToIdTransformer implements DataTransformerInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    public function transform($value): ?string
    {
        if ($value instanceof ProductInterface) {
            return $value->getId();
        }

        return null;
    }

    public function reverseTransform($value): ProductInterface
    {
        return $this->productRepository->get($value);
    }

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function setProductRepository(ProductRepositoryInterface $productRepository): void
    {
        $this->productRepository = $productRepository;
    }
}
