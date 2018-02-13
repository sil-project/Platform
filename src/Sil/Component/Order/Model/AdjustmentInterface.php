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

interface AdjustmentInterface
{
    /**
     * Gets current adjustment value.
     *
     * @return float
     */
    public function getValue(): float;

    /**
     * Gets adjutment label.
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Gets the target of adjustment.
     *
     * @return AdjustableInterface
     */
    public function getTarget(): AdjustableInterface;

    /**
     * Gets the calculated absolute value of adjustment.
     *
     * @return PriceInterface
     */
    public function getAdjustmentAbsolutePriceValue(): PriceInterface;

    /**
     * Gets the total price of adjustment.
     *
     * @return PriceInterface
     */
    public function getTotal(): PriceInterface;

    /**
     * Sets the final value of adjustment.
     *
     * @param PriceInterface $price
     */
    public function setTotal(PriceInterface $price): void;

    /**
     * @return AdjustmentStrategyInterface
     */
    public function getStrategy(): AdjustmentStrategyInterface;
}
