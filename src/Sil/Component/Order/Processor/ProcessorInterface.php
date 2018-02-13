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

use Sil\Component\Order\Model\OrderInterface;

interface ProcessorInterface
{
    /**
     * Process the whole order.
     *
     * @param OrderInterface $order
     */
    public function process(OrderInterface $order): void;

    /**
     * Calculate order items totals.
     *
     * @param OrderInterface $order
     */
    public function calculateOrderItemTotals(): void;

    /**
     * Calculate order items adjusted totals.
     *
     * @param OrderInterface $order
     */
    public function calculateOrderItemAdjustedTotals(): void;

    /**
     * Calculate order total (items totals with their adjustments).
     *
     * @param OrderInterface $order
     */
    public function calculateOrderTotal(): void;

    /**
     * Calculate order adjusted (order totals with its adjustments).
     *
     * @param OrderInterface $order
     */
    public function calculateOrderAdjustedTotal(): void;
}
