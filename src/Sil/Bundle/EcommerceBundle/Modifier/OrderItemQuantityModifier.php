<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\EcommerceBundle\Modifier;

use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Component\Order\Model\OrderItemInterface;
use Sylius\Component\Order\Factory\OrderItemUnitFactoryInterface;

class OrderItemQuantityModifier implements OrderItemQuantityModifierInterface
{
    /**
     * @var OrderItemUnitFactoryInterface
     */
    private $orderItemUnitFactory;

    /**
     * @param OrderItemUnitFactoryInterface $orderItemUnitFactory
     */
    public function __construct(OrderItemUnitFactoryInterface $orderItemUnitFactory)
    {
        $this->orderItemUnitFactory = $orderItemUnitFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function modify(OrderItemInterface $orderItem, int $targetQuantity): void
    {
        $currentQuantity = $orderItem->getQuantity();
        if (0 >= $targetQuantity || $currentQuantity === $targetQuantity) {
            return;
        }

        if ($targetQuantity < $currentQuantity) {
            $this->decreaseUnitsNumber($orderItem, !$orderItem->isBulk() ? ($currentQuantity - $targetQuantity) : 1);
        } elseif ($targetQuantity > $currentQuantity) {
            $this->increaseUnitsNumber($orderItem, !$orderItem->isBulk() ? ($targetQuantity - $currentQuantity) : 1);
        }
    }

    /**
     * @param OrderItemInterface $orderItem
     * @param int                $increaseBy
     */
    private function increaseUnitsNumber(OrderItemInterface $orderItem, int $increaseBy): void
    {
        if (!$orderItem->isBulk()) {
            for ($i = 0; $i < $increaseBy; ++$i) {
                $this->orderItemUnitFactory->createForItem($orderItem);
            }
        } else {
            if ($orderItem->getUnits()->count() === 0) {
                $this->orderItemUnitFactory->createForItem($orderItem);
            }
        }
    }

    /**
     * @param OrderItemInterface $orderItem
     * @param int                $decreaseBy
     */
    private function decreaseUnitsNumber(OrderItemInterface $orderItem, int $decreaseBy): void
    {
        if (!$orderItem->isBulk()) {
            foreach ($orderItem->getUnits() as $unit) {
                if (0 >= $decreaseBy--) {
                    break;
                }

                $orderItem->removeUnit($unit);
            }
        } else {
            if ($totalItemCount = $orderItem->getUnits()->count() > 1) {
                for ($i = $totalItemCount; $i < 1; ++$i) {
                    $orderItem->removeUnit($orderItem->getUnits()->get($i));
                }
            }
        }
    }
}
