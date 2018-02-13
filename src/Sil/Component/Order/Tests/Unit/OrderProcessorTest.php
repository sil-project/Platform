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

use PHPUnit\Framework\TestCase;
use Sil\Component\Order\Tests\Unit\Fixtures\Fixtures;
use Sil\Component\Order\Processor\OrderProcessor;

class OrderProcessorTest extends TestCase
{
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = new Fixtures();
    }

    public function test_order_processor()
    {
        $orderRepository = $this->fixtures->getOrderRepository();
        $order = $orderRepository->findOneBy(['code.value' => 'FA00000001']);

        $orderProcessor = new OrderProcessor();

        $this->assertEquals($order->getTotal()->getValue(), 0.0);
        $this->assertEquals($order->getAdjustedTotal()->getValue(), 0.0);

        $orderProcessor->process($order);

        $this->assertEquals($order->getAdjustedTotal()->getValue(), $order->expectedTotalWithAdjustments);
        $this->assertEquals($order->getTotal()->getValue(), $order->expectedTotal);
    }
}
