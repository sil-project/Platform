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

class Product implements ProductInterface
{
    /**
     * Product name.
     *
     * @var string
     */
    protected $name;

    /**
     * Product unique identifyer code.
     *
     * @var CodeInterface
     */
    protected $code;

    /**
     * Product is enabled (available).
     *
     * @var bool
     */
    protected $enabled = true;

    /**
     * The product variants.
     *
     * @var Collection|ProductVariant[]
     */
    protected $variants;

    /**
     * Collection of Attributes.
     *
     * @var Collection|Attribute[]
     */
    protected $attributes;

    /**
     * Collection of option labels for this kind of product.
     *
     * @var Collection|OptionType[]
     */
    protected $optionTypes;

    public function __construct(CodeInterface $code, string $name)
    {
        $this->variants = new ArrayCollection();
        $this->attributes = new ArrayCollection();
        $this->optionTypes = new ArrayCollection();

        $this->code = $code;
        $this->name = $name;
    }

    /**
     * @return CodeInterface
     */
    public function getCode(): CodeInterface
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return array|ProductVariant[]
     */
    public function getVariants(): array
    {
        return $this->variants->getValues();
    }

    public function hasVariant(ProductVariantInterface $variant): bool
    {
        return $this->variants->contains($variant);
    }

    public function addVariant(ProductVariantInterface $variant): void
    {
        if ($this->variants->contains($variant)) {
            throw new InvalidArgumentException(sprintf('Variant « %s » for product « %s » already exists', $variant->getName(), $this->getName()));
        }
        $this->variants->add($variant);
    }

    public function removeVariant(ProductVariantInterface $variant): void
    {
        if (!$this->variants->contains($variant)) {
            throw new InvalidArgumentException(sprintf('Variant « %s » for product « %s » does not exists', $variant->getName(), $this->getName()));
        }
        $this->variants->removeElement($variant);
    }

    /**
     * @return array|Attribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes->getValues();
    }

    public function addAttribute(Attribute $attribute): void
    {
        if ($this->attributes->contains($attribute) || $attribute->hasProduct($this)) {
            throw new InvalidArgumentException(sprintf('Attribute « %s » for product « %s » already exists', $attribute->getName(), $this->getName()));
        }
        $this->attributes->add($attribute);
        $attribute->addProduct($this);
    }

    public function removeAttribute(Attribute $attribute): void
    {
        if (!$this->attributes->contains($attribute) || !$attribute->hasProduct($this)) {
            throw new InvalidArgumentException(sprintf('Attribute « %s » for product « %s » does not exists', $attribute->getName(), $this->getName()));
        }
        $this->attributes->removeElement($attribute);
        $attribute->removeProduct($this);
    }

    /**
     * @return array|OptionType[]
     */
    public function getOptionTypes(): array
    {
        return $this->optionTypes->getValues();
    }

    public function addOptionType(OptionType $optionType): void
    {
        if ($this->optionTypes->contains($optionType)) {
            throw new InvalidArgumentException(sprintf('OptionType « %s » for product « %s » is already set', $optionType->getName(), $this->getName()));
        }
        $this->optionTypes->add($optionType);
        $optionType->addProduct($this);
    }

    public function removeOptionType(OptionType $optionType): void
    {
        if (!$this->optionTypes->contains($optionType)) {
            throw new InvalidArgumentException(sprintf('OptionType « %s » for product « %s » is not set', $optionType->getName(), $this->getName()));
        }
        $this->optionTypes->removeElement($optionType);
        $optionType->removeProduct($this);
    }
}
