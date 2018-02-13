<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Invoice\Model;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Blast\Component\Resource\Model\ResourceInterface;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class Invoice implements ResourceInterface
{
    /**
     * code.
     *
     * @var string
     */
    protected $code;

    /**
     * delivery date.
     *
     * @var DateTime
     */
    protected $date;

    /**
     * currency.
     *
     * @var CurrencyInterface
     */
    protected $currency;

    /**
     * status.
     *
     * @var InvoiceStatus
     */
    protected $status;

    /**
     * tax rate.
     *
     * @var float
     */
    protected $taxRate;

    /**
     * seller information.
     *
     * @var string
     */
    protected $sellerInfo;

    /**
     * due date.
     *
     * @var DateTime
     */
    protected $dueDate;

    /**
     * taxes amount.
     *
     * @var float
     */
    protected $taxesAmount;

    /**
     * subtotal.
     *
     * @var float
     */
    protected $subTotal;

    /**
     * grand total.
     *
     * @var float
     */
    protected $grandTotal;

    /**
     * paid amount.
     *
     * @var float
     */
    protected $paidAmount;

    /**
     * due amount.
     *
     * @var float
     */
    protected $dueAmount;

    /**
     * payment terms.
     *
     * @var string
     */
    protected $paymentTerms;

    /**
     * created at.
     *
     * @var DateTime
     */
    protected $createdAt;

    /**
     * customer.
     *
     * @var CustomerInfoInterface
     */
    protected $customer;

    /**
     * invoice items.
     *
     * @var Collection|InvoiceItemInterface[]
     */
    protected $items;

    /**
     * invoice adjustments.
     *
     * @var Collection|InvoiceAdjustmentInterface[]
     */
    protected $adjustments;

    /**
     * @param string                $code
     * @param DateTime              $date
     * @param CurrencyInterface     $currency
     * @param float                 $taxRate
     * @param string                $sellerInfo
     * @param DateTime              $dueDate
     * @param float                 $taxes
     * @param float                 $subTotal
     * @param float                 $grandTotal
     * @param float                 $paidAmount
     * @param float                 $dueAmount
     * @param string                $paymentTerms
     * @param CustomerInfoInterface $customer
     */
    public function __construct(
        string $code,
        \DateTime $date,
        CurrencyInterface $currency,
        float $taxRate,
        string $sellerInfo,
        \DateTime $dueDate,
        float $taxes,
        float $subTotal,
        float $grandTotal,
        float $paidAmount,
        float $dueAmount,
        string $paymentTerms,
        CustomerInfoInterface $customer
    ) {
        $this->code = $code;
        $this->date = $date;
        $this->currency = $currency;
        $this->taxRate = $taxRate;
        $this->sellerInfo = $sellerInfo;
        $this->dueDate = $dueDate;
        $this->taxes = $taxes;
        $this->subTotal = $subTotal;
        $this->grandTotal = $grandTotal;
        $this->paidAmount = $paidAmount;
        $this->dueAmount = $dueAmount;
        $this->paymentTerms = $paymentTerms;
        $this->customer = $customer;
        $this->createdAt = new \DateTime();
        $this->items = new ArrayCollection();
        $this->adjustments = new ArrayCollection();
        $this->status = new InvoiceStatus();
    }

    /**
     * Get the value of code.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Get the value of delivery date.
     *
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * Get the value of currency.
     *
     * @return CurrencyInterface
     */
    public function getCurrency(): CurrencyInterface
    {
        return $this->currency;
    }

    /**
     * Get the value of status.
     *
     * @return InvoiceStatus
     */
    public function getStatus(): InvoiceStatus
    {
        return $this->status;
    }

    /**
     * Set the value of status.
     *
     * @param InvoiceStatus $status
     */
    public function setStatus(InvoiceStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * Get the value of tax rate.
     *
     * @return float
     */
    public function getTaxRate(): float
    {
        return $this->taxRate;
    }

    /**
     * Get the value of seller information.
     *
     * @return string
     */
    public function getSellerInfo(): string
    {
        return $this->sellerInfo;
    }

    /**
     * Get the value of due date.
     *
     * @return DateTime
     */
    public function getDueDate(): DateTime
    {
        return $this->dueDate;
    }

    /**
     * Get the value of taxes amount.
     *
     * @return float taxes amount
     */
    public function getTaxesAmount(): taxes
    {
        return $this->taxes;
    }

    /**
     * Get the value of subtotal.
     *
     * @return float
     */
    public function getSubTotal(): float
    {
        return $this->subTotal;
    }

    /**
     * Get the value of grand total.
     *
     * @return float
     */
    public function getGrandTotal(): float
    {
        return $this->grandTotal;
    }

    /**
     * Get the value of paid amount.
     *
     * @return float
     */
    public function getPaidAmount(): float
    {
        return $this->paidAmount;
    }

    /**
     * Get the value of due amount.
     *
     * @return float
     */
    public function getDueAmount(): float
    {
        return $this->dueAmount;
    }

    /**
     * Get the value of payment terms.
     *
     * @return string
     */
    public function getPaymentTerms(): string
    {
        return $this->paymentTerms;
    }

    /**
     * Get the value of created at.
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * Get the value of customer.
     *
     * @return CustomerInfoInterface
     */
    public function getCustomer(): CustomerInfoInterface
    {
        return $this->customer;
    }

    /**
     * Get the value of invoice items.
     *
     * @return array|InvoiceItemInterface[]
     */
    public function getItems(): array
    {
        return $this->items->getValues();
    }

    /**
     * Add an Item to the collection.
     *
     * @param InvoiceItemInterface $item
     */
    public function addItem(InvoiceItemInterface $item): void
    {
        if ($this->items->contains($item)) {
            throw new \InvalidArgumentException('This invoice already contains this item');
        }

        $this->items->add($item);
    }

    /**
     *  Remove an Item from the collection.
     *
     * @param InvoiceItemInterface $item
     */
    public function removeItem(InvoiceItemInterface $item): void
    {
        if (!$this->hasItem($item)) {
            throw new \InvalidArgumentException('Trying to remove an item that does not belong to this invoice');
        }

        $this->items->removeElement($item);
    }

    /**
     * Check if an Item exists in the Collection.
     *
     * @param InvoiceItemInterface $item
     *
     * @return bool whether the Item exists
     */
    public function hasItem(InvoiceItemInterface $item): bool
    {
        return $this->items->contains($item);
    }

    /**
     * Get the value of invoice adjustments.
     *
     * @return array|InvoiceAdjustmentInterface[]
     */
    public function getAdjustments(): array
    {
        return $this->adjustments->getValues();
    }

    /**
     * Add an adjustment to the collection.
     *
     * @param InvoiceAdjustmentInterface $adjustment
     */
    public function addAdjustment(InvoiceAdjustmentInterface $adjustment): void
    {
        if ($this->adjustments->contains($adjustment)) {
            throw new \InvalidArgumentException('This invoice already contains this adjustment');
        }

        $this->adjustments->add($adjustment);
    }

    /**
     *  Remove an adjustment from the collection.
     *
     * @param InvoiceAdjustmentInterface $adjustment
     */
    public function removeAdjustment(InvoiceAdjustmentInterface $adjustment): void
    {
        if (!$this->hasAdjustment($adjustment)) {
            throw new \InvalidArgumentException('Trying to remove a non existing adjustment');
        }

        $this->adjustments->removeElement($adjustment);
    }

    /**
     * Check if an adjustment exists in the Collection.
     *
     * @param InvoiceAdjustmentInterface $adjustment
     *
     * @return bool whether the Adjustment exists
     */
    public function hasAdjustment(InvoiceAdjustmentInterface $adjustment): bool
    {
        return $this->adjustments->contains($adjustment);
    }
}
