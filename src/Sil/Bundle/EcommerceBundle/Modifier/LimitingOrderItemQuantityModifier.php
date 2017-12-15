<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Modifier;

use Sylius\Component\Order\Model\OrderItemInterface;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;

class LimitingOrderItemQuantityModifier implements OrderItemQuantityModifierInterface
{
    /**
     * @var OrderItemQuantityModifierInterface
     */
    private $decoratedOrderItemQuantityModifier;

    /**
     * @var OrderItemQuantityModifierInterface
     */
    private $bulkOrderItemQuantityModifier;

    /**
     * @var int
     */
    private $limit;

    /**
     * @param OrderItemQuantityModifierInterface $decoratedOrderItemQuantityModifier
     * @param int                                $limit
     */
    public function __construct(OrderItemQuantityModifierInterface $decoratedOrderItemQuantityModifier, int $limit)
    {
        $this->decoratedOrderItemQuantityModifier = $decoratedOrderItemQuantityModifier;
        $this->limit = $limit;
    }

    /**
     * {@inheritdoc}
     */
    public function modify(OrderItemInterface $orderItem, int $targetQuantity): void
    {
        if (!$orderItem->isBulk()) {
            $targetQuantity = min($targetQuantity, $this->limit);
            $this->decoratedOrderItemQuantityModifier->modify($orderItem, $targetQuantity);
        } else {
            $this->bulkOrderItemQuantityModifier->modify($orderItem, $targetQuantity);
        }
    }

    /**
     * @param OrderItemQuantityModifierInterface bulkOrderItemQuantityModifier
     */
    public function setBulkOrderItemQuantityModifier(OrderItemQuantityModifierInterface $bulkOrderItemQuantityModifier): void
    {
        $this->bulkOrderItemQuantityModifier = $bulkOrderItemQuantityModifier;
    }
}
