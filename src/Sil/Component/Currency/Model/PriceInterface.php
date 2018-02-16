<?php


declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Currency\Model;

interface PriceInterface
{
    /**
     * Get the price value.
     *
     * @return float
     */
    public function getValue(): float;

    /**
     * Get the currency for current price.
     *
     * @return CurrencyInterface
     */
    public function getCurrency(): CurrencyInterface;

    /**
     * Returns a string representation of price.
     *
     * @return string
     */
    public function __toString(): string;
}
