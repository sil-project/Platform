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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getCode(): OrderCode
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderItems(): array
    {
        return $this->items->getValues();
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getTotal(): PriceInterface
    {
        return $this->total;
    }

    /**
     * {@inheritdoc}
     */
    public function setTotal(PriceInterface $total): void
    {
        $this->getState()->allowDataChanges();

        $this->total = $total;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdjustedTotal(): PriceInterface
    {
        return $this->adjustedTotal;
    }

    /**
     * {@inheritdoc}
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
