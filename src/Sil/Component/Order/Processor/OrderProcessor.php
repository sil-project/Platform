<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Order\Processor;

use Sil\Component\Order\Model\Price;
use Sil\Component\Order\Model\OrderInterface;
use Sil\Component\Currency\Model\CurrencyInterface;

class OrderProcessor implements ProcessorInterface
{
    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * Current currency.
     *
     * @var CurrencyInterface
     */
    protected $currency;

    /**
     * {@inheritdoc}
     */
    public function process(OrderInterface $order): void
    {
        $this->order = $order;
        $this->currency = $this->order->getCurrency();

        $this->calculateOrderItemTotals();
        $this->calculateOrderItemAdjustedTotals();
        $this->calculateOrderTotal();
        $this->calculateOrderAdjustedTotal();
    }

    /**
     * {@inheritdoc}
     */
    public function calculateOrderItemTotals(): void
    {
        foreach ($this->order->getOrderItems() as $item) {
            $item->setTotal(new Price($this->currency, $item->getQuantity()->getValue() * $item->getUnitPrice()->getValue()));
            $item->setAdjustedTotal(new Price($this->currency, $item->getQuantity()->getValue() * $item->getUnitPrice()->getValue()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function calculateOrderItemAdjustedTotals(): void
    {
        foreach ($this->order->getOrderItems() as $item) {
            foreach ($item->getAdjustments() as $adjustment) {
                $adjustment->getStrategy()->adjust($adjustment);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function calculateOrderTotal(): void
    {
        $orderTotal = 0.0;

        foreach ($this->order->getOrderItems() as $item) {
            $orderTotal += $item->getAdjustedTotal()->getValue();
        }

        $this->order->setTotal(new Price($this->currency, $orderTotal));
        $this->order->setAdjustedTotal(new Price($this->currency, $orderTotal));
    }

    /**
     * {@inheritdoc}
     */
    public function calculateOrderAdjustedTotal(): void
    {
        foreach ($this->order->getAdjustments() as $adjustment) {
            $adjustment->getStrategy()->adjust($adjustment);
        }
    }
}
