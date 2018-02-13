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
use Blast\Component\Code\Model\CodeInterface;

class ProductVariant implements ProductVariantInterface, ResourceInterface
{
    /**
     * Product unique identifyer code.
     *
     * @var CodeInterface
     */
    protected $code;

    /**
     * Product name.
     *
     * @var string
     */
    protected $name;

    /**
     * Product is enabled (available).
     *
     * @var bool
     */
    protected $enabled = true;

    /**
     * The Product associated with this variant.
     *
     * @var Product
     */
    protected $product;

    /**
     * Collection of Options.
     *
     * @var Collection|Option[]
     */
    protected $options;

    public function __construct(ProductInterface $product, CodeInterface $code, $name)
    {
        $this->options = new ArrayCollection();

        $this->name = $name;
        $this->code = $code;

        $this->product = $product;
        $product->addVariant($this);
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
    public function getEnabled(): bool
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
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return array|Option[]
     */
    public function getOptions(): array
    {
        return $this->options->getValues();
    }

    public function addOption(Option $option): void
    {
        if ($this->options->contains($option)) {
            throw new InvalidArgumentException(sprintf('Option « %s - %s » for product variant « %s » already exists', $option->getName(), $option->getValue(), $this->getName()));
        }
        $this->options->add($option);
    }

    public function removeOption(Option $option): void
    {
        if (!$this->options->contains($option)) {
            throw new InvalidArgumentException(sprintf('Option « %s - %s » for product variant « %s » does not exists', $option->getName(), $option->getValue(), $this->getName()));
        }
        $this->options->removeElement($option);
    }
}
