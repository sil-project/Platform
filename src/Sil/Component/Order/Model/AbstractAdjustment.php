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

abstract class AbstractAdjustment implements AdjustmentInterface, ResourceInterface
{
    /**
     * Label of adjustment.
     *
     * @var string
     */
    protected $label;

    /**
     * The adjustment value.
     *
     * @var float
     */
    protected $value;

    /**
     * The adjustment calculated total.
     *
     * @var PriceInterface
     */
    protected $total;

    /**
     * The strategy used for adjustment calculation.
     *
     * @var AdjustmentTypeInterface
     */
    protected $type;

    /**
     * The strategy used for adjustment calculation.
     *
     * @var AdjustmentStrategyInterface
     */
    protected $strategy;

    public function __construct(AdjustmentStrategyInterface $strategy, AdjustmentTypeInterface $type, CurrencyInterface $currency, string $label, float $value)
    {
        $this->strategy = $strategy;
        $this->type = $type;
        $this->label = $label;
        $this->value = $value;
        $this->total = new Price($currency, 0.0);
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
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * {@inheritdoc}
     */
    public function getStrategy(): AdjustmentStrategyInterface
    {
        return $this->strategy;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotal(): PriceInterface
    {
        return $this->total;
    }

    /**
     * {@inheritdoc}
     */
    public function setTotal(PriceInterface $price): void
    {
        $this->total = $price;
    }

    /**
     * @return AdjustmentTypeInterface
     */
    public function getType(): AdjustmentTypeInterface
    {
        return $this->type;
    }
}
