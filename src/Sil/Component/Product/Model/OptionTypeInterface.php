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

interface OptionTypeInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * @return array|OptionInterface[]
     */
    public function getOptions(): array;

    /**
     * Adds an option to option type.
     *
     * @param OptionInterface $option
     */
    public function addOption(OptionInterface $option): void;

    /**
     * Removes an option form option type.
     *
     * @param OptionInterface $option
     */
    public function removeOption(OptionInterface $option): void;

    /**
     * @return array|ProductInterface[]
     */
    public function getProducts(): array;

    /**
     * Adds a product to option type.
     *
     * @param ProductInterface $product
     */
    public function addProduct(ProductInterface $product): void;

    /**
     * Removes a product from option type.
     *
     * @param ProductInterface $product
     */
    public function removeProduct(ProductInterface $product): void;
}
