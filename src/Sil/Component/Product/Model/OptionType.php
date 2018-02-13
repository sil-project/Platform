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

class OptionType implements ResourceInterface
{
    /**
     * Name of attribute.
     *
     * @var string
     */
    protected $name;

    /**
     * Collection of options (values) for this kind of option.
     *
     * @var Collection|Option[]
     */
    protected $options;

    /**
     * Collection of Products that uses this OptionType.
     *
     * @var Collection|Product[]
     */
    protected $products;

    public function __construct($name)
    {
        $this->options = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->name = $name;
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
     * @return array|Option[]
     */
    public function getOptions(): array
    {
        return $this->options->getValues();
    }

    public function addOption(Option $option): void
    {
        if ($this->options->contains($option)) {
            throw new InvalidArgumentException(sprintf('Option « %s » for option label « %s » already exists', $option->getName(), $this->getName()));
        }
        $this->options->add($option);
    }

    public function removeOption(Option $option): void
    {
        if (!$this->options->contains($option)) {
            throw new InvalidArgumentException(sprintf('Option « %s » for option label « %s » does not exists', $option->getName(), $this->getName()));
        }
        $this->options->removeElement($option);
    }

    /**
     * @return array|Products[]
     */
    public function getProducts(): array
    {
        return $this->products->getValues();
    }

    public function addProduct(ProductInterface $product): void
    {
        if ($this->products->contains($product)) {
            throw new InvalidArgumentException(sprintf('Product « %s » for option label « %s » already exists', $product->getName(), $this->getName()));
        }
        $this->products->add($product);
    }

    public function removeProduct(ProductInterface $product): void
    {
        if (!$this->products->contains($product)) {
            throw new InvalidArgumentException(sprintf('Product « %s » for option label « %s » does not exists', $product->getName(), $this->getName()));
        }
        $this->products->removeElement($product);
    }
}
