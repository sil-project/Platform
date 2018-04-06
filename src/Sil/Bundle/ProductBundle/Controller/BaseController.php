<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ProductBundle\Controller;

use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\FormFactoryInterface;
use Sil\Component\Product\Repository\OptionRepositoryInterface;
use Sil\Component\Product\Repository\ProductRepositoryInterface;
use Sil\Component\Product\Repository\ProductVariantRepositoryInterface;
use Sil\Component\Product\Repository\AttributeRepositoryInterface;
use Sil\Component\Product\Repository\AttributeTypeRepositoryInterface;
use Sil\Component\Product\Repository\OptionTypeRepositoryInterface;
use Blast\Bundle\UIBundle\Breadcrumb\BreadcrumbBuilder;
use Blast\Component\Resource\Model\ResourceInterface;

abstract class BaseController extends Controller
{
    /**
     * @var int
     */
    protected $perPage = 20;

    /**
     * @var string
     */
    protected $productClass;

    /**
     * @var string
     */
    protected $productVariantClass;

    /**
     * @var string
     */
    protected $attributeClass;

    /**
     * @var string
     */
    protected $attributeTypeClass;

    /**
     * @var string
     */
    protected $optionClass;

    /**
     * @var string
     */
    protected $optionTypeClass;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var ProductVariantRepositoryInterface
     */
    protected $productVariantRepository;

    /**
     * @var AttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     * @var AttributeTypeRepositoryInterface
     */
    protected $attributeTypeRepository;

    /**
     * @var OptionRepositoryInterface
     */
    protected $optionRepository;

    /**
     * @var OptionTypeRepositoryInterface
     */
    protected $optionTypeRepository;

    /**
     * @var BreadcrumbBuilder
     */
    protected $breadcrumbBuilder;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * fetch resource or throws a 404.
     *
     * @param string $productId
     *
     * @return ResourceInterface
     */
    protected function findProductOr404(string $productId): ResourceInterface
    {
        try {
            return $this->productRepository->get($productId);
        } catch (InvalidArgumentException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    /**
     * fetch resource or throws a 404.
     *
     * @param string $productVariantId
     *
     * @return ResourceInterface
     */
    protected function findProductVariantOr404(string $productVariantId): ResourceInterface
    {
        try {
            return $this->productVariantRepository->get($productVariantId);
        } catch (InvalidArgumentException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    /**
     * fetch resource or throws a 404.
     *
     * @param string $attributeId
     *
     * @return ResourceInterface
     */
    protected function findAttributeOr404(string $attributeId): ResourceInterface
    {
        try {
            return $this->attributeRepository->get($attributeId);
        } catch (InvalidArgumentException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    /**
     * fetch resource or throws a 404.
     *
     * @param string $attributeTypeId
     *
     * @return ResourceInterface
     */
    protected function findAttributeTypeOr404(string $attributeTypeId): ResourceInterface
    {
        try {
            return $this->attributeTypeRepository->get($attributeTypeId);
        } catch (InvalidArgumentException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    /**
     * fetch resource or throws a 404.
     *
     * @param string $optionId
     *
     * @return ResourceInterface
     */
    protected function findOptionOr404(string $optionId): ResourceInterface
    {
        try {
            return $this->optionRepository->get($optionId);
        } catch (InvalidArgumentException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    /**
     * fetch resource or throws a 404.
     *
     * @param string $optionTypeId
     *
     * @return ResourceInterface
     */
    protected function findOptionTypeOr404(string $optionTypeId): ResourceInterface
    {
        try {
            return $this->optionTypeRepository->get($optionTypeId);
        } catch (InvalidArgumentException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    /**
     * @param string $productClass
     */
    public function setProductClass(string $productClass): void
    {
        $this->productClass = $productClass;
    }

    /**
     * @param string $productVariantClass
     */
    public function setProductVariantClass(string $productVariantClass): void
    {
        $this->productVariantClass = $productVariantClass;
    }

    /**
     * @param string $attributeClass
     */
    public function setAttributeClass(string $attributeClass): void
    {
        $this->attributeClass = $attributeClass;
    }

    /**
     * @param string $attributeTypeClass
     */
    public function setAttributeTypeClass(string $attributeTypeClass): void
    {
        $this->attributeTypeClass = $attributeTypeClass;
    }

    /**
     * @param string $optionClass
     */
    public function setOptionClass(string $optionClass): void
    {
        $this->optionClass = $optionClass;
    }

    /**
     * @param string $optionTypeClass
     */
    public function setOptionTypeClass(string $optionTypeClass): void
    {
        $this->optionTypeClass = $optionTypeClass;
    }

    /**
     * @param BreadcrumbBuilder $breadcrumbBuilder
     */
    public function setBreadcrumbBuilder(BreadcrumbBuilder $breadcrumbBuilder): void
    {
        $this->breadcrumbBuilder = $breadcrumbBuilder;
    }

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function setProductRepository(ProductRepositoryInterface $productRepository): void
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param ProductVariantRepositoryInterface $productVariantRepository
     */
    public function setProductVariantRepository(ProductVariantRepositoryInterface $productVariantRepository): void
    {
        $this->productVariantRepository = $productVariantRepository;
    }

    /**
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function setAttributeRepository(AttributeRepositoryInterface $attributeRepository): void
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @param AttributeTypeRepositoryInterface $attributeTypeRepository
     */
    public function setAttributeTypeRepository(AttributeTypeRepositoryInterface $attributeTypeRepository): void
    {
        $this->attributeTypeRepository = $attributeTypeRepository;
    }

    /**
     * @param OptionRepositoryInterface $optionRepository
     */
    public function setOptionRepository(OptionRepositoryInterface $optionRepository): void
    {
        $this->optionRepository = $optionRepository;
    }

    /**
     * @param OptionTypeRepositoryInterface $optionTypeRepository
     */
    public function setOptionTypeRepository(OptionTypeRepositoryInterface $optionTypeRepository): void
    {
        $this->optionTypeRepository = $optionTypeRepository;
    }

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function setFormFactory(FormFactoryInterface $formFactory): void
    {
        $this->formFactory = $formFactory;
    }
}
