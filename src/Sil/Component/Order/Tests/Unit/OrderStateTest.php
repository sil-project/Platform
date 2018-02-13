<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Product\Tests\Unit;

use SM\SMException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Sil\Component\Order\Factory\OrderFactory;
use Sil\Component\Order\Model\OrderState;
use Sil\Component\Order\Tests\Unit\Fixtures\Fixtures;

class OrderStateTest extends TestCase
{
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = new Fixtures();
    }

    public function test_new_order_is_draft()
    {
        $order = OrderFactory::createOrder($this->fixtures->getOrderRepository(), $this->fixtures->getDummyAccount(), $this->fixtures->getCurrencyRepository()->getByCode('EUR'));

        $this->assertEquals($order->getState()->getValue(), OrderState::DRAFT);
    }

    public function test_validate_transition()
    {
        $order = $this->fixtures->getOrderRepository()->findOneBy(['code.value' => 'FA00000001']);

        $this->assertEquals($order->getState()->getValue(), OrderState::DRAFT);

        $order->beValidated();

        $this->assertEquals($order->getState()->getValue(), OrderState::VALIDATED);
    }

    public function test_forbidden_transition()
    {
        $order = $this->fixtures->getOrderRepository()->findOneBy(['code.value' => 'FA00000001']);

        $this->assertEquals($order->getState()->getValue(), OrderState::DRAFT);

        $this->expectException(SMException::class);

        $order->beFulfilled();
    }

    public function test_manual_change_of_wrong_state()
    {
        $order = $this->fixtures->getOrderRepository()->findOneBy(['code.value' => 'FA00000001']);

        $this->expectException(InvalidArgumentException::class);

        $order->getState()->setValue('a wrong state');
    }
}
