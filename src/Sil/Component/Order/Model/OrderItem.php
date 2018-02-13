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

use Blast\Component\Resource\Model\ResourceInterface;
use Sil\Component\Uom\Model\UomQty;
use Doctrine\Common\Collections\ArrayCollection;

class OrderItem implements OrderItemInterface, AdjustableInterface, ResourceInterface
{
    /**
     * Label of order item.
     *
     * @var string
     */
    protected $label;

    /**
     * Short description of order item.
     *
     * @var string
     */
    protected $description;

    /**
     * The order.
     *
     * @var OrderInterface
     */
    protected $order;

    /**
     * Order item unit price.
     *
     * @var PriceInterface
     */
    protected $unitPrice;

    /**
     * The quantity represented by this item.
     *
     * @var UomQty
     */
    protected $quantity;

    /**
     * Order item total price.
     *
     * @var PriceInterface
     */
    protected $total;

    /**
     * Order item total price.
     *
     * @var PriceInterface
     */
    protected $adjustedTotal;

    /**
     * Collection of order item's adjustments (order adjustments are not included).
     *
     * @var Collection|OrderItemAdjustment[]
     */
    protected $adjustments;

    public function __construct(OrderInterface $order, string $label, UomQty $quantity, PriceInterface $unitPrice)
    {
        $this->adjustments = new ArrayCollection();

        $this->order = $order;
        $this->label = $label;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;

        $this->total = new Price($order->getCurrency(), $this->unitPrice->getValue() * $this->quantity->getValue());
        $this->adjustedTotal = new Price($order->getCurrency(), $this->total->getValue());

        $order->addOrderItem($this);
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return OrderInterface
     */
    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    /**
     * @return PriceInterface
     */
    public function getUnitPrice(): PriceInterface
    {
        return $this->unitPrice;
    }

    /**
     * @return UomQty
     */
    public function getQuantity(): UomQty
    {
        return $this->quantity;
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
        $this->getOrder()->getState()->allowDataChanges();

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
        $this->getOrder()->getState()->allowDataChanges();

        $this->adjustedTotal = $adjustedTotal;
    }

    /**
     * @return array|OrderItemAdjustment[]
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
        $this->getOrder()->getState()->allowDataChanges();

        if (!$adjustment->getTarget() instanceof self) {
            throw new InvalidArgumentException(sprintf('Adjustment « %s » does not apply on object of type « %s »', $adjustment->getLabel(), self::class));
        }

        if ($adjustment->getTarget() !== $this) {
            throw new InvalidArgumentException(sprintf('Adjustment « %s » does not have the correct target order item « %s »', $adjustment->getLabel(), $this->getLabel()));
        }

        if ($this->adjustments->contains($adjustment)) {
            throw new InvalidArgumentException(sprintf('Adjustment « %s » is already attached to the order item « %s »', $adjustment->getLabel(), $this->getLabel()));
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
        $this->getOrder()->getState()->allowDataChanges();

        if (!$this->adjustments->contains($adjustment)) {
            throw new InvalidArgumentException(sprintf('Adjustment « %s » does not belong to order item « %s »', $adjustment->getLabel(), $this->getLabel()));
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
}
