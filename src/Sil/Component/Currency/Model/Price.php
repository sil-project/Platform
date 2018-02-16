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

class Price implements PriceInterface
{
    /**
     * The amount of current price.
     *
     * @var float
     */
    protected $value;

    /**
     * Currency of current price.
     *
     * @var CurrencyInterface
     */
    protected $currency;

    public function __construct(CurrencyInterface $currency, float $value)
    {
        $this->currency = $currency;
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrency(): CurrencyInterface
    {
        return $this->currency;
    }

    public function __toString(): string
    {
        return sprintf('%.02f %s', $this->value, $this->currency);
    }
}
