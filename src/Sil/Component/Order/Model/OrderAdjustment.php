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

class OrderAdjustment extends AbstractAdjustment implements ResourceInterface
{
    /**
     * @var OrderInterface
     */
    protected $order;

    public function __construct(OrderInterface $order, AdjustmentStrategyInterface $strategy, AdjustmentTypeInterface $type, string $label, float $value)
    {
        $this->order = $order;

        parent::__construct($strategy, $type, $order->getCurrency(), $label, $value);

        $order->addAdjustment($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getTarget(): AdjustableInterface
    {
        return $this->order;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdjustmentAbsolutePriceValue(): PriceInterface
    {
        if ($this->getType()->isRate()) {
            $absoluteTotal = $this->value * $this->getTarget()->getTotal()->getValue();
        } else {
            $absoluteTotal = $this->value;
        }

        return new Price($this->order->getCurrency(), $absoluteTotal);
    }
}
