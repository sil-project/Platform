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

use DateTime;
use Sylius\Component\Currency\Model\CurrencyInterface;

interface OrderInterface
{
    /**
     * Retreive Order code.
     *
     * @return OrderCode
     */
    public function getCode(): OrderCode;

    /**
     * @return CurrencyInterface
     */
    public function getCurrency(): CurrencyInterface;

    /**
     * @return array|OrderItemInterface[]
     */
    public function getOrderItems(): array;

    /**
     * Add order item to current order.
     *
     * @param OrderItemInterface $orderItem
     *
     * @throws InvalidArgumentException|DomainException
     */
    public function addOrderItem(OrderItemInterface $orderItem): void;

    /**
     * Remove order item.
     *
     * @param OrderItemInterface $orderItem
     *
     * @throws InvalidArgumentException|DomainException
     */
    public function removeOrderItem(OrderItemInterface $orderItem): void;

    /**
     * Gets Order created date.
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime;

    /**
     * @return PriceInterface
     */
    public function getTotal(): PriceInterface;

    /**
     * @param PriceInterface $total
     */
    public function setTotal(PriceInterface $total): void;

    /**
     * @return PriceInterface
     */
    public function getAdjustedTotal(): PriceInterface;

    /**
     * @param PriceInterface $adjustedTotal
     */
    public function setAdjustedTotal(PriceInterface $adjustedTotal): void;
}
