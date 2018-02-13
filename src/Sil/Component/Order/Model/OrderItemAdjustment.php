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

class OrderItemAdjustment extends AbstractAdjustment implements ResourceInterface
{
    /**
     * @var OrderItemInterface
     */
    protected $orderItem;

    public function __construct(OrderItemInterface $orderItem, AdjustmentStrategyInterface $strategy, AdjustmentTypeInterface $type, string $label, float $value)
    {
        $this->orderItem = $orderItem;

        parent::__construct($strategy, $type, $orderItem->getOrder()->getCurrency(), $label, $value);

        $this->orderItem->addAdjustment($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getTarget(): AdjustableInterface
    {
        return $this->orderItem;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdjustmentAbsolutePriceValue(): PriceInterface
    {
        $absoluteTotal = 0.0;
        if ($this->getType()->isRate()) {
            $absoluteTotal = $this->value * $this->orderItem->getTotal()->getValue();
        } else {
            $absoluteTotal = $this->value;
        }

        return new Price($this->orderItem->getOrder()->getCurrency(), $absoluteTotal);
    }
}
