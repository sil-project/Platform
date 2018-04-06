<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ProductBundle\Repository;

use Doctrine\Common\Collections\Collection;
use Sil\Component\Product\Model\ProductInterface;
use Sil\Component\Product\Repository\ProductVariantRepositoryInterface;
use Blast\Component\Code\Repository\CodeAwareRepositoryInterface;
use Blast\Bundle\ResourceBundle\Doctrine\ORM\Repository\ResourceRepository;
use Blast\Component\Resource\Model\ResourceInterface;
use Blast\Component\Code\Model\CodeInterface;

class ProductVariantRepository extends ResourceRepository implements ProductVariantRepositoryInterface, CodeAwareRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getVariantsForProduct(ProductInterface $product): Collection
    {
        return $this->findBy(['product' => $product]);
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveVariantsForProduct(ProductInterface $product): Collection
    {
        return $this->findBy(['product' => $product, 'active' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function getInactiveVariantsForProduct(ProductInterface $product): Collection
    {
        return $this->findBy(['product' => $product, 'active' => false]);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByCode(CodeInterface $code): ?ResourceInterface
    {
        return $this->findOneBy(['code' => $code]);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByCodeValue(string $codeValue): ?ResourceInterface
    {
        return $this->findOneBy(['code.value' => $codeValue]);
    }
}
