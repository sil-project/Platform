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
use Blast\Component\Resource\Model\ResourceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Option implements OptionInterface, ResourceInterface
{
    /**
     * Value for attribute.
     *
     * @var string
     */
    protected $value;

    /**
     * The attribute for current value.
     *
     * @var OptionTypeInterface
     */
    protected $optionType;

    /**
     * The collection of product variants that use this option.
     *
     * @var Collection|ProductVariantInterface[]
     */
    protected $productVariants;

    public function __construct(OptionTypeInterface $optionType, $value)
    {
        $this->productVariants = new ArrayCollection();

        $this->optionType = $optionType;
        $optionType->addOption($this); // Update bi-directionnal relationship
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(): string
    {
        return (string) $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionType(): OptionTypeInterface
    {
        return $this->optionType;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptionType(OptionTypeInterface $optionType): void
    {
        $this->optionType = $optionType;
    }

    /**
     * @return array|ProductVariantInterface[]
     */
    public function getProductVariants(): array
    {
        return $this->productVariants->getValues();
    }

    /**
     * @param ProductVariantInterface $productVariant
     *
     * @throws InvalidArgumentException
     */
    public function addProductVariant(ProductVariantInterface $productVariant): void
    {
        if ($this->productVariants->contains($productVariant)) {
            throw new InvalidArgumentException(sprintf('ProductVariant « %s » is already in Option inverse relation', $productVariant));
        }
        $this->productVariants->add($productVariant);
    }

    /**
     * @param ProductVariantInterface $productVariant
     *
     * @throws InvalidArgumentException
     */
    public function removeProductVariant(ProductVariantInterface $productVariant): void
    {
        if (!$this->productVariants->contains($productVariant)) {
            throw new InvalidArgumentException(sprintf('ProductVariant « %s » is not in Option inverse relation', $productVariant));
        }
        $this->productVariants->removeElement($productVariant);
    }
}
