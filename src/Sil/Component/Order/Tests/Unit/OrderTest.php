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

use DomainException;
use PHPUnit\Framework\TestCase;
use Sil\Component\Order\Generator\OrderCodeGenerator;
use Sil\Component\Order\Factory\OrderFactory;
use Sil\Component\Order\Model\OrderInterface;
use Sil\Component\Order\Tests\Unit\Fixtures\Fixtures;
use Sil\Component\Order\Tests\Unit\Repository\OrderRepository;

class OrderTest extends TestCase
{
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = new Fixtures();
    }

    public function test_first_order_create_process()
    {
        $orderRepository = new OrderRepository(OrderInterface::class);
        $codeGenerator = new OrderCodeGenerator($orderRepository);

        $code = $codeGenerator->generate();

        $this->assertEquals($code, 'FA00000001');
    }

    public function test_not_first_order_create_process()
    {
        $orderRepository = $this->fixtures->getOrderRepository();
        $codeGenerator = new OrderCodeGenerator($orderRepository);

        $code = $codeGenerator->generate();

        $this->assertEquals($code, 'FA00000003');
    }

    public function test_new_order_from_factory()
    {
        $order = OrderFactory::createOrder($this->fixtures->getOrderRepository(), $this->fixtures->getDummyAccount(), $this->fixtures->getCurrencyRepository()->getByCode('EUR'));

        $this->assertInstanceOf(OrderInterface::class, $order);
    }

    public function test_draft_order_is_editable()
    {
        $order = $this->fixtures->getOrderRepository()->findOneBy(['code.value' => 'FA00000001']);

        $order->setSource('change not allowed');

        $this->assertEquals($order->getSource(), 'change not allowed');
    }

    public function test_validated_order_is_not_editable()
    {
        $order = $this->fixtures->getOrderRepository()->findOneBy(['code.value' => 'FA00000001']);

        $order->beValidated();

        $this->expectException(DomainException::class);

        $order->setSource('change not allowed');
    }
}
