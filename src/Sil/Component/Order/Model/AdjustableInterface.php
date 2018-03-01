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

interface AdjustableInterface
{
    /**
     * @return array|AdjustmentInterface[]
     */
    public function getAdjustments(): array;

    /**
     * Add adjustment to current object.
     *
     * @param AdjustmentInterface $adjustment
     *
     * @throws InvalidArgumentException|DomainException
     */
    public function addAdjustment(AdjustmentInterface $adjustment): void;

    /**
     * Removes object's adjustment.
     *
     * @param AdjustmentInterface $adjustment
     *
     * @throws InvalidArgumentException|DomainException
     */
    public function removeAdjustment(AdjustmentInterface $adjustment): void;

    /**
     * Sets the adjusted total price of target element.
     *
     * @param PriceInterface $adjustedTotal
     */
    public function setAdjustedTotal(PriceInterface $adjustedTotal): void;
}
