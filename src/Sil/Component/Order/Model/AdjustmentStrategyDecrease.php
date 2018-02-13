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

class AdjustmentStrategyDecrease implements AdjustmentStrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function affectTotal(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function adjust(AdjustmentInterface $adjustment): void
    {
        $absolutePrice = $adjustment->getAdjustmentAbsolutePriceValue();
        $adjustedPrice = new Price($absolutePrice->getCurrency(), $absolutePrice->getValue() * -1.0);

        $adjustment->setTotal($adjustedPrice);

        $targetTotal = $adjustment->getTarget()->getTotal();

        $adjustment->getTarget()->setAdjustedTotal(new Price($targetTotal->getCurrency(), $targetTotal->getValue() + $adjustedPrice->getValue()));
    }
}
