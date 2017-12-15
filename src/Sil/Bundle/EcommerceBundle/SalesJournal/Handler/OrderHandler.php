<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\SalesJournal\Handler;

use Sil\Bundle\EcommerceBundle\Entity\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sil\Bundle\EcommerceBundle\Entity\Invoice;
use Sil\Bundle\EcommerceBundle\SalesJournal\Strategy\StrategyInterface;

class OrderHandler extends AbstractHandler
{
    /**
     * @var StrategyInterface
     */
    private $orderItemStrategy;

    /**
     * @var StrategyInterface
     */
    private $orderAdjustmentStrategy;

    /**
     * @var StrategyInterface
     */
    private $customerStrategy;

    public function generateItemsFromOrder(OrderInterface $order, Invoice $invoice, $operationType)
    {
        if ($operationType !== Invoice::TYPE_DEBIT && $operationType !== Invoice::TYPE_CREDIT) {
            throw new \Exception('Operation type must be Invoice::TYPE_DEBIT or Invoice::TYPE_CREDIT');
        }

        $this->items = [];

        // Generate Order Items sales journal item(s)

        /** @var OrderItemInterface $orderItem */
        foreach ($order->getItems() as $orderItem) {
            $label = $this->orderItemStrategy->getLabel($orderItem);
            $salesJournalItem = $this->handleSalesJournalItem($label, $order, $invoice);
            $this->orderItemStrategy->handleOperation($salesJournalItem, $orderItem);
        }

        // Generate Order Adjustments sales journal item(s)

        /** @var AdjustmentInterface $adjustment */
        foreach ($order->getAdjustmentsRecursively() as $adjustment) {
            $label = $this->orderAdjustmentStrategy->getLabel($adjustment);
            $salesJournalItem = $this->handleSalesJournalItem($label, $order, $invoice);
            $this->orderAdjustmentStrategy->handleOperation($salesJournalItem, $adjustment);
        }

        // Generate Customer sales journal item

        $label = $this->customerStrategy->getLabel($order);
        $salesJournalItem = $this->handleSalesJournalItem($label, $order, $invoice);
        $this->customerStrategy->handleOperation($salesJournalItem, $order);

        // Persisting all sales journal items

        foreach ($this->items as $item) {
            $this->salesJournalItemRepository->add($item);
        }
    }

    /**
     * @param StrategyInterface orderItemStrategy
     */
    public function setOrderItemStrategy(StrategyInterface $orderItemStrategy): void
    {
        $this->orderItemStrategy = $orderItemStrategy;
    }

    /**
     * @param StrategyInterface $orderAdjustmentStrategy
     */
    public function setOrderAdjustmentStrategy(StrategyInterface $orderAdjustmentStrategy): void
    {
        $this->orderAdjustmentStrategy = $orderAdjustmentStrategy;
    }

    /**
     * @param StrategyInterface $customerStrategy
     */
    public function setCustomerStrategy(StrategyInterface $customerStrategy): void
    {
        $this->customerStrategy = $customerStrategy;
    }
}
