<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\DataFixtures\Sylius\Factory;

use LisemBundle\Entity\SilEcommerceBundle\Product;
use Sil\Bundle\VarietyBundle\Entity\Variety;
use Sylius\Component\Product\Factory\ProductFactoryInterface;
use Sylius\Component\Product\Repository\ProductOptionRepositoryInterface;
use Sylius\Component\Product\Model\ProductInterface;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ProductFactory implements ProductFactoryInterface
{
    /**
     * @var ProductFactoryInterface
     */
    private $decoratedFactory;

    /**
     * @var ProductOptionRepositoryInterface
     */
    private $productOptionRepository;

    /**
     * @param ProductFactoryInterface $factory
     */
    public function __construct(ProductFactoryInterface $factory, ProductOptionRepositoryInterface $productOptionRepository)
    {
        $this->decoratedFactory = $factory;
        $this->productOptionRepository = $productOptionRepository;
    }

    /**
     * @param bool $seedsProduct
     *
     * @return Product
     */
    public function createNew($seedsProduct = false)
    {
        $product = $this->decoratedFactory->createNew();

        if ($seedsProduct) {
            $this->setDefaultOptions($product);
        }

        return $product;
    }

    /**
     * @param bool $seedsProduct
     *
     * @return Product
     */
    public function createWithVariant($seedsProduct = false): ProductInterface
    {
        $product = $this->decoratedFactory->createWithVariant();

        if ($seedsProduct) {
            $this->setDefaultOptions($product);
        }

        return $product;
    }

    /**
     * @param Variety $variety
     *
     * @return Product
     *
     * @todo   Add default taxonomy based on variety taxonomy
     */
    public function createNewForVariety(Variety $variety)
    {
        $product = $this->createNew(true);
        $product->setVariety($variety);
        $product->setName((string) $variety);
        $product->setCode(sprintf('%s-%s', $variety->getSpecies()->getCode(), $variety->getCode()));
        // TODO: default taxonomy

        return $product;
    }

    /**
     * @param Product $product
     */
    private function setDefaultOptions($product)
    {
        $packagingOption = $this->productOptionRepository->findOneBy(['code' => Product::$PACKAGING_OPTION_CODE]);
        if (!$packagingOption) {
            throw new \RuntimeException(sprintf('Product option with code "%s" does not exist in database. It is required for LISem project.'), Product::$PACKAGING_OPTION_CODE);
        }
        $product->addOption($packagingOption);
    }
}
