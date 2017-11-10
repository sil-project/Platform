<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\SalesJournal\Handler;

use Sylius\Component\Core\Model\PaymentInterface;
use Sil\Bundle\EcommerceBundle\Entity\Invoice;
use Sil\Bundle\EcommerceBundle\SalesJournal\Strategy\StrategyInterface;

class PaymentHandler extends AbstractHandler
{
    /**
     * @var StrategyInterface
     */
    private $paymentStrategy;

    /**
     * @var StrategyInterface
     */
    private $customerStrategy;

    public function generateItemsFromPayment(PaymentInterface $payment, $operationType)
    {
        if ($operationType !== Invoice::TYPE_DEBIT && $operationType !== Invoice::TYPE_CREDIT) {
            throw new \Exception('Operation type must be Invoice::TYPE_DEBIT or Invoice::TYPE_CREDIT');
        }

        $this->items = [];

        $order = $payment->getOrder();
        $invoice = $order->getLastDebitInvoice();

        // Generate Bank debit

        $label = $this->paymentStrategy->getLabel($payment);
        $salesJournalItem = $this->handleSalesJournalItem($label, $order, $invoice, $payment);
        $this->paymentStrategy->handleOperation($salesJournalItem, $payment);

        // Generate Customer credit sales journal item

        $label = $this->customerStrategy->getLabel($order);
        $salesJournalItem = $this->handleSalesJournalItem($label, $order, $invoice, $payment);
        $this->customerStrategy->handleOperation($salesJournalItem, $payment);

        // Persisting all sales journal items

        foreach ($this->items as $item) {
            $this->salesJournalItemRepository->add($item);
        }
    }

    /**
     * @param StrategyInterface $paymentStrategy
     */
    public function setPaymentStrategy(StrategyInterface $paymentStrategy): void
    {
        $this->paymentStrategy = $paymentStrategy;
    }

    /**
     * @param StrategyInterface $customerStrategy
     */
    public function setCustomerStrategy(StrategyInterface $customerStrategy): void
    {
        $this->customerStrategy = $customerStrategy;
    }
}
