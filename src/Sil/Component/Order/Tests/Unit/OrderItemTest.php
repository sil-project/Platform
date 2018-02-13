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

class OrderItemTest extends TestCase
{
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = new Fixtures();
    }

    public function test_order_item_have_correct_total_without_adjustments()
    {
        $orderRepository = $this->fixtures->getOrderRepository();
        $order = $orderRepository->findOneBy(['code.value' => 'FA00000001']);

        $orderItemData = $this->fixtures->getRawData()['Orders']['FA00000001']['items'];

        foreach ($order->getOrderItems() as $item) {
            $this->assertEquals($item->getTotal()->getValue(), $orderItemData[$item->getLabel()]['expectedTotal']);
        }
    }
}
