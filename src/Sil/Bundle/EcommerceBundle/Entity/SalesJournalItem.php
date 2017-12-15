<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Entity;

use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\BaseEntity;

class SalesJournalItem
{
    use BaseEntity;

    /**
     * @var \DateTimeInterface
     */
    protected $operationDate;

    /**
     * @var string
     */
    protected $account = 'N/A';

    /**
     * @var string
     */
    protected $label;

    /**
     * @var int
     */
    protected $debit = 0;

    /**
     * @var int
     */
    protected $credit = 0;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var Invoice
     */
    protected $invoice;

    /**
     * @var Payment
     */
    protected $payment;

    public function __construct()
    {
        $this->operationDate = new \DateTime();
    }

    /**
     * @return \DateTimeInterface
     */
    public function getOperationDate(): \DateTimeInterface
    {
        return $this->operationDate;
    }

    /**
     * @param \DateTimeInterface $operationDate
     */
    public function setOperationDate(\DateTimeInterface $operationDate): void
    {
        $this->operationDate = $operationDate;
    }

    /**
     * @return string
     */
    public function getAccount(): string
    {
        return $this->account;
    }

    /**
     * @param string $account
     */
    public function setAccount(string $account): void
    {
        $this->account = $account;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return int
     */
    public function getDebit(): int
    {
        return $this->debit;
    }

    /**
     * @param int $debit
     */
    public function setDebit(int $debit): void
    {
        $this->debit = $debit;
    }

    /**
     * @param int $debit
     */
    public function addDebit(int $debit): void
    {
        $this->debit += $debit;
    }

    /**
     * @return int
     */
    public function getCredit(): int
    {
        return $this->credit;
    }

    /**
     * @param int $credit
     */
    public function setCredit(int $credit): void
    {
        $this->credit = $credit;
    }

    /**
     * @param int $credit
     */
    public function addCredit(int $credit): void
    {
        $this->credit += $credit;
    }

    /**
     * @return Order
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }

    /**
     * @return Invoice
     */
    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    /**
     * @param Invoice $invoice
     */
    public function setInvoice(Invoice $invoice): void
    {
        $this->invoice = $invoice;
    }

    /**
     * @return Payment
     */
    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    /**
     * @param Payment $payment
     */
    public function setPayment(Payment $payment): void
    {
        $this->payment = $payment;
    }
}
