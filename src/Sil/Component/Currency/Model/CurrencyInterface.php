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

interface CurrencyInterface
{
    /**
     * Returns the currency string code (ISO 4217).
     *
     * @return string
     */
    public function getCode(): string;

    /**
     * Returns the currency numeric code (ISO 4217).
     *
     * @return int
     */
    public function getIsoCode(): int;

    /**
     * Gets the representating symbol of currency.
     *
     * @return string
     */
    public function getSymbol(): string;

    /**
     * Lists all managed currencies by this component.
     *
     * @return array
     */
    public function getManagedCurrencies(): array;

    /**
     * Returns a string representation of currency.
     *
     * @return string
     */
    public function __toString(): string;
}
