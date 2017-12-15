<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\SalesJournal;

use Sil\Bundle\EcommerceBundle\Entity\OrderInterface;
use Sil\Bundle\EcommerceBundle\Entity\Invoice;
use Sil\Bundle\EcommerceBundle\Entity\Payment;
use Sil\Bundle\EcommerceBundle\SalesJournal\Handler\OrderHandler;
use Sil\Bundle\EcommerceBundle\SalesJournal\Handler\PaymentHandler;
use Sil\Bundle\EcommerceBundle\SalesJournal\Factory\SalesJournalItemFactory;

class SalesJournalService
{
    /**
     * @var SalesJournalItemFactory
     */
    private $salesJournalItemFactory;

    /**
     * @var OrderHandler
     */
    private $orderHandler;

    /**
     * @var PaymentHandler
     */
    private $paymentHandler;

    public function traceCreditInvoice(OrderInterface $order, Invoice $invoice)
    {
        $this->orderHandler->generateItemsFromOrder($order, $invoice, Invoice::TYPE_DEBIT);
    }

    public function traceDebitInvoice(OrderInterface $order, Invoice $invoice)
    {
        $this->orderHandler->generateItemsFromOrder($order, $invoice, Invoice::TYPE_CREDIT);
    }

    public function tracePayment(OrderInterface $order, Payment $payment)
    {
        $this->paymentHandler->generateItemsFromPayment($payment, Invoice::TYPE_DEBIT);
    }

    public function traceRefund(OrderInterface $order, Payment $payment)
    {
        $this->paymentHandler->generateItemsFromPayment($payment, Invoice::TYPE_CREDIT);
    }

    /**
     * @param SalesJournalItemFactory $salesJournalItemFactory
     */
    public function setSalesJournalItemFactory(SalesJournalItemFactory $salesJournalItemFactory): void
    {
        $this->salesJournalItemFactory = $salesJournalItemFactory;
    }

    /**
     * @param OrderHandler orderHandler
     */
    public function setOrderHandler(OrderHandler $orderHandler): void
    {
        $this->orderHandler = $orderHandler;
    }

    /**
     * @param PaymentHandler $paymentHandler
     */
    public function setPaymentHandler(PaymentHandler $paymentHandler): void
    {
        $this->paymentHandler = $paymentHandler;
    }
}
