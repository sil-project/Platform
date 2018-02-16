<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Product\Tests\Unit\Fixtures;

use DateTime;
use Sil\Component\Product\Model\Attribute;
use Sil\Component\Product\Model\AttributeType;
use Sil\Component\Product\Model\ProductVariant;
use Sil\Component\Product\Model\ProductInterface;
use Sil\Component\Product\Model\ProductVariantInterface;
use Sil\Component\Product\Model\Option;
use Sil\Component\Product\Model\OptionType;
use Sil\Component\Product\Model\Product;
use Sil\Component\Product\Factory\CodeFactory;
use Sil\Component\Product\Repository\OptionRepositoryInterface;
use Sil\Component\Product\Tests\Unit\Repository\OptionRepository;
use Sil\Component\Product\Repository\ProductRepositoryInterface;
use Sil\Component\Product\Tests\Unit\Repository\ProductRepository;
use Sil\Component\Product\Repository\AttributeRepositoryInterface;
use Sil\Component\Product\Tests\Unit\Repository\AttributeRepository;
use Sil\Component\Product\Repository\OptionTypeRepositoryInterface;
use Sil\Component\Product\Tests\Unit\Repository\OptionTypeRepository;
use Sil\Component\Product\Repository\AttributeTypeRepositoryInterface;
use Sil\Component\Product\Tests\Unit\Repository\AttributeTypeRepository;
use Sil\Component\Product\Repository\ProductVariantRepositoryInterface;
use Sil\Component\Product\Tests\Unit\Repository\ProductVariantRepository;
use function BenTools\CartesianProduct\cartesian_product;

class Fixtures
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ProductVariantRepositoryInterface
     */
    private $variantRepository;

    /**
     * @var AttributeTypeRepositoryInterface
     */
    private $attributeTypeRepository;

    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepository;

    /**
     * @var OptionTypeRepositoryInterface
     */
    private $optionTypeRepository;

    /**
     * @var OptionRepositoryInterface
     */
    private $optionRepository;

    private $rawData = [];

    public function __construct()
    {
        $this->rawData = [
            'Reusable attributes' => [
                'Brand' => [
                    'BrandOne', 'BrandTwo', 'BrandThree',
                ],
                'Model' => [
                    'ModelOne', 'ModelTwo', 'ModelThree',
                ],
            ],
            'Basics attributes' => [
                'Shoelace length'    => AttributeType::TYPE_FLOAT,
                'Polymer rate'       => AttributeType::TYPE_PERCENT,
                'With documentation' => AttributeType::TYPE_BOOLEAN,
                'Start age'          => AttributeType::TYPE_INTEGER,
                'Certification date' => AttributeType::TYPE_DATE,
                'Release date'       => AttributeType::TYPE_DATETIME,
            ],
            'Size' => [
                'XS', 'S', 'M', 'L', 'XL',
            ],
            'Shoe Size' => range(38, 42),
            'Color'     => [
                'Red', 'Green', 'Blue',
            ],
            'Products' => [
                [
                    'code' => 'SHOE001',
                    'name' => 'Shoes 001',
                ], [
                    'code' => 'TSHT001',
                    'name' => 'Tshirt 001',
                ],
            ],
        ];

        $this->productRepository = new ProductRepository(ProductInterface::class);
        $this->variantRepository = new ProductVariantRepository(ProductVariantInterface::class);
        $this->attributeTypeRepository = new AttributeTypeRepository(AttributeType::class);
        $this->attributeRepository = new AttributeRepository(Attribute::class);
        $this->optionTypeRepository = new OptionTypeRepository(OptionType::class);
        $this->optionRepository = new OptionRepository(Option::class);

        $this->generateFixtures();
    }

    private function generateFixtures()
    {
        $this->generateAttributes();
        $this->generateOptions();
        $this->generateProducts();
        $this->generateVariants();
    }

    private function generateAttributes()
    {
        /*
         * BRAND
         */

        $attributeType = new AttributeType('Brand');
        $attributeType->setReusable(true);

        $this->getAttributeTypeRepository()->add($attributeType);

        foreach ($this->rawData['Reusable attributes']['Brand'] as $brand) {
            $attribute = new Attribute($attributeType, $brand);
            $this->getAttributeRepository()->add($attribute);
        }

        /*
         * MODEL
         */

        $attributeType = new AttributeType('Model');
        $attributeType->setReusable(true);

        foreach ($this->rawData['Reusable attributes']['Model'] as $model) {
            $attribute = new Attribute($attributeType, $model);
            $this->getAttributeRepository()->add($attribute);
        }

        $this->getAttributeTypeRepository()->add($attributeType);

        /*
         * BASIC ATTRIBUTES
         */

        foreach ($this->rawData['Basics attributes'] as $name => $valueType) {
            $attributeType = new AttributeType($name, $valueType);
            $this->getAttributeTypeRepository()->add($attributeType);
        }
    }

    private function generateOptions()
    {
        /*
         * SIZE
         */

        $optionTypes = new OptionType('Size');

        foreach ($this->rawData['Size'] as $size) {
            $option = new Option($optionTypes, $size);
            $this->getOptionRepository()->add($option);
        }

        $this->getOptionTypeRepository()->add($optionTypes);

        /*
         * SHOE_SIZE
         */

        $optionTypes = new OptionType('Shoe Size');

        foreach ($this->rawData['Shoe Size'] as $size) {
            $option = new Option($optionTypes, $size);
            $this->getOptionRepository()->add($option);
        }

        $this->getOptionTypeRepository()->add($optionTypes);

        /*
         * COLOR
         */

        $optionTypes = new OptionType('Color');

        foreach ($this->rawData['Color'] as $size) {
            $option = new Option($optionTypes, $size);
            $this->getOptionRepository()->add($option);
        }

        $this->getOptionTypeRepository()->add($optionTypes);
    }

    private function generateProducts()
    {
        /*
         * SHOES
         */

        $productCode = CodeFactory::generateProductCode($this->rawData['Products'][0]['code']);

        $product = new Product($productCode, $this->rawData['Products'][0]['name']);

        $optionTypes = $this->getOptionTypeRepository()->findBy(['name' => 'Shoe Size']);
        $optionTypes = array_merge($this->getOptionTypeRepository()->findBy(['name' => 'Color']), $optionTypes);

        foreach ($optionTypes as $optionType) {
            $product->addOptionType($optionType);
        }
        $brandOne = $this->getAttributeRepository()->findOneBy(['value' => 'BrandOne', 'attributeType.name' => 'Brand']);
        $product->addAttribute($brandOne);

        $modelTwo = $this->getAttributeRepository()->findOneBy(['value' => 'ModelTwo', 'attributeType.name' => 'Model']);
        $product->addAttribute($modelTwo);

        foreach ([
            'Shoelace length' => 32.5,
            'Polymer rate'    => 0.95,
            'Release date'    => new DateTime('2020-05-01'),
        ] as $attributeName => $attributeValue) {
            $this->addAttributeToProduct($product, $attributeName, $attributeValue);
        }

        $this->getProductRepository()->add($product);

        /*
         * T-SHIRTS
         */

        $productCode = CodeFactory::generateProductCode($this->rawData['Products'][1]['code']);

        $product = new Product($productCode, $this->rawData['Products'][1]['name']);

        $optionTypes = $this->getOptionTypeRepository()->findBy(['name' => 'Size']);
        $optionTypes = array_merge($this->getOptionTypeRepository()->findBy(['name' => 'Color']), $optionTypes);

        foreach ($optionTypes as $optionType) {
            $product->addOptionType($optionType);
        }

        foreach ([
            'With documentation' => true,
            'Start age'          => 12,
            'Certification date' => new DateTime('2020-05-01 12:12:42'),
        ] as $attributeName => $attributeValue) {
            $this->addAttributeToProduct($product, $attributeName, $attributeValue);
        }

        $this->getProductRepository()->add($product);
    }

    private function addAttributeToProduct(ProductInterface $product, $attributeName, $attributeValue)
    {
        $attributeType = $this->getAttributeTypeRepository()->findOneBy(['name' => $attributeName]);

        $attribute = new Attribute($attributeType, $attributeValue);
        $product->addAttribute($attribute);
    }

    private function generateVariants()
    {
        $product = $this->getProductRepository()->findOneBy(['code.value' => 'TSHT001']);

        $options = cartesian_product($this->getProductOptionsAsArray($product))->asArray();

        foreach ($options as $option) {
            $variantName = $product->getName();

            $variantCode = CodeFactory::generateProductVariantCode($product, $option);

            $variant = new ProductVariant($product, $variantCode, $variantName);

            foreach ($option as $optionType => $optionValue) {
                $variantName .= ' ' . $optionValue->getValue();

                $variant->addOption($optionValue);
            }

            $variant->setName($variantName);

            $this->getVariantRepository()->add($variant);
        }
    }

    private function getProductOptionsAsArray(ProductInterface $product): array
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
     * @return ProductRepositoryInterface
     */
    public function getProductRepository(): ProductRepositoryInterface
    {
        return $this->productRepository;
    }

    /**
     * @return ProductVariantRepositoryInterface
     */
    public function getVariantRepository(): ProductVariantRepositoryInterface
    {
        return $this->variantRepository;
    }

    /**
     * @return AttributeTypeRepositoryInterface
     */
    public function getAttributeTypeRepository(): AttributeTypeRepositoryInterface
    {
        return $this->attributeTypeRepository;
    }

    /**
     * @return AttributeRepositoryInterface
     */
    public function getAttributeRepository(): AttributeRepositoryInterface
    {
        return $this->attributeRepository;
    }

    /**
     * @return OptionTypeRepositoryInterface
     */
    public function getOptionTypeRepository(): OptionTypeRepositoryInterface
    {
        return $this->optionTypeRepository;
    }

    /**
     * @return OptionRepositoryInterface
     */
    public function getOptionRepository(): OptionRepositoryInterface
    {
        return $this->optionRepository;
    }

    /**
     * @return array
     */
    public function getRawData(): array
    {
        return $this->rawData;
    }
}
