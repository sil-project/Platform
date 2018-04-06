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
use Sil\Bundle\ProductBundle\Form\Transformer\Exception\TransformationFailedException;
use Sil\Component\Product\Model\ProductInterface;
use Sil\Component\Product\Generator\ProductCodeGeneratorInterface;
use Sil\Component\Product\Repository\ProductRepositoryInterface;

class ArrayToProductTransformer implements DataTransformerInterface
{
    /**
     * @var string
     */
    protected $productClass;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var array
     */
    protected $mandatoryFields = ['name', 'code_value'];

    /**
     * @var ProductCodeGeneratorInterface
     */
    protected $productCodeGenerator;

    /**
     * {@inheritdoc}
     */
    public function transform($value): array
    {
        if (!$value instanceof ProductInterface) {
            $product = [
                'id'          => null,
                'name'        => null,
                'code_value'  => null,
                'description' => null,
                'enabled'     => null,
            ];
        } else {
            $product = [
                'id'          => $value->getId(),
                'name'        => $value->getName(),
                'code_value'  => $value->getCode(),
                'description' => $value->getDescription(),
                'enabled'     => $value->getEnabled(),
            ];
        }

        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value): ProductInterface
    {
        if (isset($value['id'])) {
            $product = $this->productRepository->get($value['id']);
        } else {
            if (count(array_intersect(array_keys($value), $this->mandatoryFields)) != count($this->mandatoryFields)) {
                throw new TransformationFailedException(
                    ProductInterface::class,
                    $value,
                    $this->mandatoryFields
                );
            }

            if (!isset($value['code_value'])) {
                $code = $this->productCodeGenerator->generate($value['name']);
            } else {
                $code = $value['code_value'];
            }

            $product = new $this->productClass($code, $value['name']);
        }

        // Setting data from submitted ones
        $product->setDescription($value['description']);
        $product->setEnabled((bool) $value['enabled']);

        foreach ($value['optionTypes'] as $optType) {
            $product->addOptionType($optType);
        }

        return $product;
    }

    /**
     * @param string $productClass
     */
    public function setProductClass(string $productClass): void
    {
        $this->productClass = $productClass;
    }

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function setProductRepository(ProductRepositoryInterface $productRepository): void
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param ProductCodeGeneratorInterface $productCodeGenerator
     */
    public function setProductCodeGenerator(ProductCodeGeneratorInterface $productCodeGenerator): void
    {
        $this->productCodeGenerator = $productCodeGenerator;
    }
}
