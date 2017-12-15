<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Entity;

use Sylius\Component\Core\Model\OrderItem as BaseOrderItem;

class OrderItem extends BaseOrderItem
{
    /**
     * @var bool
     */
    protected $bulk = false;

    /**
     * {@inheritdoc}
     */
    public function recalculateUnitsTotal(): void
    {
        if (!$this->isBulk()) {
            parent::recalculateUnitsTotal();

            return;
        }
        $this->unitsTotal = round(($this->quantity * $this->unitPrice) / 1000);

        $this->recalculateTotal();
    }

    protected function recalculateTotal(): void
    {
        if (!$this->isBulk()) {
            parent::recalculateTotal();

            return;
        }
        $this->total = $this->unitsTotal + $this->adjustmentsTotal;

        if ($this->total < 0) {
            $this->total = 0;
        }

        if (null !== $this->order) {
            $this->order->recalculateItemsTotal();
        }
    }

    /**
     * @return bool
     */
    public function isBulk(): bool
    {
        return $this->bulk;
    }

    /**
     * @param bool bulk
     */
    public function setBulk(bool $bulk): void
    {
        $this->bulk = $bulk;
    }

    /**
     * {@inheritdoc}
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubtotal(): int
    {
        if (!$this->isBulk()) {
            return parent::getSubtotal();
        }

        return round($this->getDiscountedUnitPrice() * ($this->quantity / 1000));
    }
}
