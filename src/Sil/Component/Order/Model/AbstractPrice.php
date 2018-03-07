<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Order\Model;

use Blast\Component\Resource\Model\ResourceInterface;
use Sil\Component\Currency\Model\CurrencyInterface;

// @TODO: Move into PriceComponent

class AbstractPrice implements PriceInterface, ResourceInterface
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
}
