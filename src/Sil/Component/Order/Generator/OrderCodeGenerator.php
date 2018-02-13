<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Order\Generator;

use Blast\Component\Code\Generator\AbstractCodeGenerator;
use Sil\Component\Order\Repository\OrderRepositoryInterface;
use Sil\Component\Order\Model\OrderCode;
use Sil\Component\Order\Model\OrderCodeInterface;

class OrderCodeGenerator extends AbstractCodeGenerator
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Generate a new order code.
     *
     * @return OrderCodeInterface
     */
    public function generate(): OrderCodeInterface
    {
        $latestOrder = $this->orderRepository->findLatestOrder();

        if ($latestOrder === null) {
            // First time generating order code
            return new OrderCode('FA00000001');
        }

        $latestCode = $latestOrder->getCode();

        // Code format is /^(FA)([0-9]{8})$/
        preg_match($latestCode->getFormat(), $latestCode->getValue(), $matches);
        array_shift($matches);

        list($prefixPart, $numericPart) = $matches;

        $numericPart++; // increment order code

        return new OrderCode($prefixPart . sprintf('%08d', $numericPart));
    }
}
