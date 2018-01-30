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

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class Attribute
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
     * @var AttributeType
     */
    protected $attributeType;

    /**
     * Collection of products that uses this attribute.
     *
     * @var Collection|Product[]
     */
    protected $products;

    public function __construct(AttributeType $attributeType, $value, $name = null)
    {
        $this->products = new ArrayCollection();

        $this->name = $name;
        $this->value = $value;
        $this->attributeType = $attributeType;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name ?? $this->getAttributeType()->getName();
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    /**
     * @return AttributeType
     */
    public function getAttributeType(): AttributeType
    {
        return $this->attributeType;
    }

    /**
     * @param AttributeType $attributeType
     */
    public function setAttributeType(AttributeType $attributeType): void
    {
        $this->attributeType = $attributeType;
    }

    /**
     * @return array|Product[]
     */
    public function getProducts(): array
    {
        return $this->products->getValues();
    }

    public function addProduct(ProductInterface $product): void
    {
        if ($this->products->contains($product)) {
            throw new InvalidArgumentException(sprintf('Product « %s » already have the attribute « %s - %s »', $product->getName(), $this->getName(), $this->getValue()));
        }
        $this->products->add($product);
    }

    public function removeProduct(ProductInterface $product): void
    {
        if (!$this->products->contains($product)) {
            throw new InvalidArgumentException(sprintf('Product « %s » does not have the attribute « %s - %s »', $product->getName(), $this->getName(), $this->getValue()));
        }
        $this->products->removeElement($product);
    }

    public function hasProduct(ProductInterface $product): bool
    {
        return $this->products->contains($product);
    }
}
