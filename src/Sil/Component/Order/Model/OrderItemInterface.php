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

use Sil\Component\Uom\Model\UomQty;

interface OrderItemInterface
{
    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @return OrderInterface
     */
    public function getOrder(): OrderInterface;

    /**
     * @return PriceInterface
     */
    public function getUnitPrice(): PriceInterface;

    /**
     * @return UomQty
     */
    public function getQuantity(): UomQty;

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

    /**
     * @return array|OrderItemAdjustment[]
     */
    public function getAdjustments(): array;

    /**
     * Add adjustment to current order.
     *
     * @param AdjustmentInterface $adjustment
     *
     * @throws InvalidArgumentException|DomainException
     */
    public function addAdjustment(AdjustmentInterface $adjustment): void;

    /**
     * Remove order adjustment.
     *
     * @param AdjustmentInterface $adjustment
     *
     * @throws InvalidArgumentException|DomainException
     */
    public function removeAdjustment(AdjustmentInterface $adjustment): void;
}
