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

interface AttributeInterface
{
    /**
     * Gets the name of attribute.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Sets the name of attribute.
     *
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * Gets the specific name of attribute.
     *
     * @return string
     */
    public function getSpecificName(): ?string;

    /**
     * Sets the specific name of attribute.
     *
     * @param string $specificName
     */
    public function setSpecificName(?string $specificName = null): void;

    /**
     * Gets the attribute value.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Sets the attribute value.
     *
     * @param mixed $value
     */
    public function setValue($value): void;

    /**
     * Attribute is reusable or not.
     *
     * @return bool
     */
    public function isReusable(): bool;

    /**
     * Gets the attribute type.
     *
     * @return AttributeTypeInterface
     */
    public function getAttributeType(): AttributeTypeInterface;

    /**
     * @return array|ProductInterface[]
     */
    public function getProducts(): array;

    /**
     * Adds product to attribute.
     *
     * @param ProductInterface $product
     */
    public function addProduct(ProductInterface $product): void;

    /**
     * Removes product from attribute.
     *
     * @param ProductInterface $product
     */
    public function removeProduct(ProductInterface $product): void;
}
