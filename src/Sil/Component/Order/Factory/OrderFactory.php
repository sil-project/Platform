<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Order\Factory;

use Sil\Component\Order\Repository\OrderRepositoryInterface;
use Sil\Component\Order\Generator\OrderCodeGenerator;
use Sil\Component\Order\Model\Order;
use Sil\Component\Order\Model\OrderInterface;
use Sil\Component\Account\Model\AccountInterface;
use Sil\Component\Currency\Model\CurrencyInterface;

class OrderFactory
{
    public static function createOrder(OrderRepositoryInterface $orderRepository, AccountInterface $account, CurrencyInterface $currency): OrderInterface
    {
        $codeGenerator = new OrderCodeGenerator($orderRepository);

        return new Order($codeGenerator->generate(), $account, $currency);
    }
}
