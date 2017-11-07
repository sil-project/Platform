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

namespace Librinfo\EcommerceBundle\Services;

use Doctrine\ORM\EntityManager;
use Librinfo\EcommerceBundle\Entity\OrderInterface;
use SM\Factory\Factory;
use Librinfo\EcommerceBundle\Factory\InvoiceFactoryInterface;
use Librinfo\EcommerceBundle\Entity\Invoice;
use Librinfo\EcommerceBundle\SalesJournal\SalesJournalService;

class OrderInvoiceManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Factory
     */
    private $stateMachine;

    /**
     * @var InvoiceFactoryInterface
     */
    private $invoiceFactory;

    /**
     * @var SalesJournalService
     */
    private $salesJournalService;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        /* @todo: as it is never used in this class, it should be removed */
        $this->em = $em;
    }

    public function generateDebitInvoice(OrderInterface $object): Invoice
    {
        $invoice = $object->getLastDebitInvoice();
        if ($invoice) {
            return $invoice;
        }

        $invoice = $this->invoiceFactory->createForOrder($object, Invoice::TYPE_DEBIT);
        $this->em->persist($invoice);

        $this->salesJournalService->traceDebitInvoice($object, $invoice);
        $this->em->flush();

        return $invoice;
    }

    public function generateCreditInvoice(OrderInterface $object): Invoice
    {
        if ($object->getInvoices()->count() !== 0) {
            $invoice = $this->invoiceFactory->createForOrder($object, Invoice::TYPE_CREDIT);

            $this->em->persist($invoice);

            $this->salesJournalService->traceCreditInvoice($object, $invoice);
            $this->em->flush();
        } else {
            $invoice = new Invoice(); // dummy invoice
        }

        return $invoice;
    }

    /**
     * @param Factory stateMachine
     *
     * @return self
     *
     * @todo: should be named setStateMachineFactory
     */
    public function setStateMachine(Factory $stateMachine)
    {
        $this->stateMachine = $stateMachine;

        return $this;
    }

    /**
     * @param InvoiceFactoryInterface invoiceFactory
     */
    public function setInvoiceFactory(InvoiceFactoryInterface $invoiceFactory): void
    {
        $this->invoiceFactory = $invoiceFactory;
    }

    /**
     * @param SalesJournalService $salesJournalService
     */
    public function setSalesJournalService(SalesJournalService $salesJournalService): void
    {
        $this->salesJournalService = $salesJournalService;
    }
}
