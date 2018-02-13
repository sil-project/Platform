<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Order\Model;

use DateTime;
use InvalidArgumentException;
use DomainException;
use Blast\Component\Resource\Model\ResourceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sil\Component\Invoice\Model\InvoiceInterface;
use Sil\Component\Account\Model\AccountInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;

class Order implements OrderInterface, AdjustableInterface, OrderStateAwareInterface, ResourceInterface
{
    use OrderStateAwareTrait;

    /**
     * Creation date.
     *
     * @var DateTime
     */
    protected $createdAt;

    /**
     * The source responsible of order creation.
     *
     * @var string
     */
    protected $source;

    /**
     * Unique identifyer.
     *
     * @var OrderCode
     */
    protected $code;

    /**
     * Collection of order items.
     *
     * @var Collection|OrderItemInterface[]
     */
    protected $items;

    /**
     * Current state of order.
     *
     * @var OrderStateInterface
     */
    protected $state;

    /**
     * Collection of state changes.
     *
     * @var Collection|OrderStateHistory[]
     */
    protected $stateHistory;

    /**
     * The currency used for the order.
     *
     * @var CurrencyInterface
     */
    protected $currency;

    /**
     * The order total price.
     *
     * @var PriceInterface
     */
    protected $total;

    /**
     * The order adjusted total price.
     *
     * @var PriceInterface
     */
    protected $adjustedTotal;

    /**
     * Collection of order's invoices.
     *
     * @var Collection|InvoiceInterface[]
     */
    protected $invoices;

    /**
     * Account that holds the order.
     *
     * @var AccountInterface
     */
    protected $account;

    /**
     * Collection of order's adjustments (orderItem adjustments are not included).
     *
     * @var Collection|OrderAdjustment[]
     */
    protected $adjustments;

    public function __construct(OrderCode $code, AccountInterface $account, CurrencyInterface $currency)
    {
        $this->account = $account;

        $this->createdAt = new DateTime();
        $this->items = new ArrayCollection();
        $this->stateHistory = new ArrayCollection();
        $this->invoices = new ArrayCollection();
        $this->adjustments = new ArrayCollection();

        $this->code = $code;
        $this->state = OrderState::draft();
        $this->editable = true;
        $this->currency = $currency;
        $this->total = new Price($currency, 0.0);
        $this->adjustedTotal = new Price($currency, 0.0);

        $this->stateHistory->add(new OrderStateHistory($this, $this->state));
    }

    /**
     * Gets Order created date.
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * Gets Order source.
     *
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * Sets Order source.
     *
     * @param string $source
     *
     * @throws DomainException
     */
    public function setSource(string $source): void
    {
        $this->getState()->allowDataChanges();

        $this->source = $source;
    }

    /**
     * Retreive Order code.
     *
     * @return OrderCode
     */
    public function getCode(): OrderCode
    {
        return $this->code;
    }

    /**
     * @return array|OrderItemInterface[]
     */
    public function getOrderItems(): array
    {
        return $this->items->getValues();
    }

    /**
     * Add order item to current order.
     *
     * @param OrderItemInterface $orderItem
     *
     * @throws InvalidArgumentException|DomainException
     */
    public function addOrderItem(OrderItemInterface $orderItem): void
    {
        $this->getState()->allowDataChanges();

        if ($orderItem->getOrder() !== $this) {
            throw new InvalidArgumentException(sprintf('OrderItem « %s » does not have the correct target order « %s »', $orderItem->getLabel(), $this->getCode()));
        }

        if ($this->items->contains($orderItem)) {
            throw new InvalidArgumentException(sprintf('OrderItem « %s » is already attached to the order « %s »', $orderItem->getLabel(), $this->getCode()));
        }
        $this->items->add($orderItem);
    }

    /**
     * Remove order item.
     *
     * @param OrderItemInterface $orderItem
     *
     * @throws InvalidArgumentException|DomainException
     */
    public function removeOrderItem(OrderItemInterface $orderItem): void
    {
        $this->getState()->allowDataChanges();

        if ($orderItem->getOrder() !== $this) {
            throw new InvalidArgumentException(sprintf('OrderItem « %s » does not have the correct target order « %s »', $orderItem->getLabel(), $this->getCode()));
        }

        if (!$this->items->contains($orderItem)) {
            throw new InvalidArgumentException(sprintf('OrderItem « %s » does not belong to order « %s »', $orderItem->getLabel(), $this->getCode()));
        }
        $this->items->removeElement($orderItem);
    }

    /**
     * Check if Item already exists for this order.
     *
     * @param OrderItemInterface $orderItem
     *
     * @return bool
     */
    public function hasOrderItem(OrderItemInterface $orderItem): bool
    {
        return $this->items->contains($orderItem);
    }

    /**
     * @return array|InvoiceInterface[]
     */
    public function getInvoices(): array
    {
        return $this->invoices->getValues();
    }

    /**
     * Add invoice to current order.
     *
     * @param InvoiceInterface $invoice
     *
     * @throws InvalidArgumentException
     */
    public function addInvoice(InvoiceInterface $invoice): void
    {
        if ($this->invoices->contains($invoice)) {
            throw new InvalidArgumentException(sprintf('Invoice %s is already attached to the order « %s »', $invoice->getCode(), $this->getCode()));
        }
        $this->invoices->add($invoice);
    }

    /**
     * Remove invoice.
     *
     * @param InvoiceInterface $invoice
     *
     * @throws InvalidArgumentException
     */
    public function removeInvoice(InvoiceInterface $invoice): void
    {
        if (!$this->invoices->contains($invoice)) {
            throw new InvalidArgumentException(sprintf('Invoice « %s » is not attached to order « %s »', $invoice->getCode(), $this->getCode()));
        }
        $this->invoices->removeElement($invoice);
    }

    /**
     * Check if invoice already exists for this order.
     *
     * @param InvoiceInterface $invoice
     *
     * @return bool
     */
    public function hasInvoice(InvoiceInterface $invoice): bool
    {
        return $this->invoices->contains($invoice);
    }

    /**
     * @return array|AdjustmentInterface[]
     */
    public function getAdjustments(): array
    {
        return $this->adjustments->getValues();
    }

    /**
     * Add adjustment to current order.
     *
     * @param AdjustmentInterface $adjustment
     *
     * @throws InvalidArgumentException|DomainException
     */
    public function addAdjustment(AdjustmentInterface $adjustment): void
    {
        $this->getState()->allowDataChanges();

        if (!$adjustment->getTarget() instanceof self) {
            throw new InvalidArgumentException(sprintf('Adjustment « %s » does not apply on object of type « %s »', $adjustment->getLabel(), self::class));
        }

        if ($adjustment->getTarget() !== $this) {
            throw new InvalidArgumentException(sprintf('Adjustment « %s » does not have the correct target order « %s »', $adjustment->getLabel(), $this->getCode()));
        }

        if ($this->adjustments->contains($adjustment)) {
            throw new InvalidArgumentException(sprintf('Adjustment « %s » is already attached to the order « %s »', $adjustment->getLabel(), $this->getCode()));
        }
        $this->adjustments->add($adjustment);
    }

    /**
     * Remove order adjustment.
     *
     * @param AdjustmentInterface $adjustment
     *
     * @throws InvalidArgumentException|DomainException
     */
    public function removeAdjustment(AdjustmentInterface $adjustment): void
    {
        $this->getState()->allowDataChanges();

        if (!$adjustment->getTarget() instanceof self) {
            throw new InvalidArgumentException(sprintf('Adjustment « %s » does not apply on object of type « %s »', $adjustment->getLabel(), self::class));
        }

        if ($adjustment->getTarget() !== $this) {
            throw new InvalidArgumentException(sprintf('Adjustment « %s » does not have the correct target order « %s »', $adjustment->getLabel(), $this->getCode()));
        }

        if (!$this->adjustments->contains($adjustment)) {
            throw new InvalidArgumentException(sprintf('Adjustment « %s » does not belong to order « %s »', $adjustment->getLabel(), $this->getCode()));
        }
        $this->adjustments->removeElement($adjustment);
    }

    /**
     * Check if adjustment already exists for this order.
     *
     * @param AdjustmentInterface $adjustment
     *
     * @return bool
     */
    public function hasAdjustment(AdjustmentInterface $adjustment): bool
    {
        return $this->adjustments->contains($adjustment);
    }

    /**
     * Gets the state of current order.
     */
    public function getState(): OrderStateInterface
    {
        return $this->state;
    }

    /**
     * Get the state history of this order.
     *
     * @return array
     */
    public function getStateHistory(): array
    {
        return $this->stateHistory->getValues();
    }

    /**
     * @return PriceInterface
     */
    public function getTotal(): PriceInterface
    {
        return $this->total;
    }

    /**
     * @param PriceInterface $total
     */
    public function setTotal(PriceInterface $total): void
    {
        $this->getState()->allowDataChanges();

        $this->total = $total;
    }

    /**
     * @return PriceInterface
     */
    public function getAdjustedTotal(): PriceInterface
    {
        return $this->adjustedTotal;
    }

    /**
     * @param PriceInterface $adjustedTotal
     */
    public function setAdjustedTotal(PriceInterface $adjustedTotal): void
    {
        $this->getState()->allowDataChanges();

        $this->adjustedTotal = $adjustedTotal;
    }

    /**
     * @return AccountInterface
     */
    public function getAccount(): AccountInterface
    {
        return $this->account;
    }

    /**
     * @param AccountInterface $account
     *
     * @throws DomainException
     */
    public function setAccount(AccountInterface $account): void
    {
        $this->getState()->allowDataChanges();

        $this->account = $account;
    }

    /**
     * @return CurrencyInterface
     */
    public function getCurrency(): CurrencyInterface
    {
        return $this->currency;
    }
}
