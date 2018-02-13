<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Order\Tests\Unit;

use DomainException;
use PHPUnit\Framework\TestCase;
use Sil\Component\Order\Tests\Unit\Fixtures\Fixtures;

class AdjustmentTest extends TestCase
{
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = new Fixtures();
    }

    public function test_order_item_adjustments_have_correct_totals()
    {
        $orderRepository = $this->fixtures->getOrderRepository();
        $order = $orderRepository->findOneBy(['code.value' => 'FA00000001']);

        foreach ($order->getOrderItems() as $orderItem) {
            foreach ($orderItem->getAdjustments() as $orderItemAdjustement) {
                $this->assertEquals($orderItemAdjustement->getTotal()->getValue(), 0.0);
                $orderItemAdjustement->getStrategy()->adjust($orderItemAdjustement);
                $this->assertEquals($orderItemAdjustement->getTotal()->getValue(), $orderItemAdjustement->expectedTotal);
            }
            $this->assertEquals($orderItem->getAdjustedTotal()->getValue(), $orderItem->expectedTotalWithAdjustments);
        }
    }

    public function test_order_item_adjustments_calculus_cannot_be_done_on_validated_order()
    {
        $orderRepository = $this->fixtures->getOrderRepository();
        $order = $orderRepository->findOneBy(['code.value' => 'FA00000001']);

        $order->beValidated();

        $this->expectException(DomainException::class);

        foreach ($order->getOrderItems() as $orderItem) {
            foreach ($orderItem->getAdjustments() as $orderItemAdjustement) {
                $this->assertEquals($orderItemAdjustement->getTotal()->getValue(), 0.0);
                $orderItemAdjustement->getStrategy()->adjust($orderItemAdjustement);
                $this->assertEquals($orderItemAdjustement->getTotal()->getValue(), $orderItemAdjustement->expectedTotal);
            }
            $this->assertEquals($orderItem->getAdjustedTotal()->getValue(), $orderItem->expectedTotalWithAdjustments);
        }
    }
}
