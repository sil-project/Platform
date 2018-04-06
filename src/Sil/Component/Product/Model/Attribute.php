<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Product\Model;

use InvalidArgumentException;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Blast\Component\Resource\Model\ResourceInterface;

class Attribute implements AttributeInterface, ResourceInterface
{
    /**
     * Specialized name for this attribute.
     *
     * @var string
     */
    protected $name;

    /**
     * Value for attribute.
     *
     * @var mixed
     */
    protected $value;

    /**
     * The attribute for current value.
     *
     * @var AttributeTypeInterface
     */
    protected $attributeType;

    /**
     * Collection of products that uses this attribute.
     *
     * @var Collection|ProductInterface[]
     */
    protected $products;

    public function __construct(AttributeTypeInterface $attributeType, $value, $name = null)
    {
        $this->products = new ArrayCollection();

        $this->name = $name;
        $this->value = $value;
        $this->attributeType = $attributeType;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name ?? $this->getAttributeType()->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getSpecificName(): ?string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setSpecificName(?string $specificName = null): void
    {
        $this->name = $specificName;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value): void
    {
        // Uncomment if you wish to disallow changing value of a reusable attribute
        //
        // if ($this->getAttributeType()->isReusable()) {
        //     throw new InvalidArgumentException('The value of a reusable attribute cannot be changed');
        // }
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function isReusable(): bool
    {
        return $this->getAttributeType()->isReusable();
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeType(): AttributeTypeInterface
    {
        return $this->attributeType;
    }

    /**
     * {@inheritdoc}
     */
    public function getProducts(): array
    {
        return $this->products->getValues();
    }

    /**
     * {@inheritdoc}
     */
    public function addProduct(ProductInterface $product): void
    {
        if ($this->products->contains($product)) {
            throw new InvalidArgumentException(sprintf('Product « %s » already have the attribute « %s - %s »', $product->getName(), $this->getName(), $this->getValue()));
        }
        $this->products->add($product);
    }

    /**
     * {@inheritdoc}
     */
    public function removeProduct(ProductInterface $product): void
    {
        if (!$this->products->contains($product)) {
            throw new InvalidArgumentException(sprintf('Product « %s » does not have the attribute « %s - %s »', $product->getName(), $this->getName(), $this->getValue()));
        }
        $this->products->removeElement($product);
    }

    /**
     * Check if attribute is linked to target product.
     *
     * @param ProductInterface $product
     *
     * @return bool
     */
    public function hasProduct(ProductInterface $product): bool
    {
        return $this->products->contains($product);
    }
}
