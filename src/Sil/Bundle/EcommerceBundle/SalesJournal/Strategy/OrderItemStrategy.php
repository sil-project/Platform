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

namespace Librinfo\EcommerceBundle\SalesJournal\Strategy;

use Sylius\Component\Core\Model\OrderItemInterface;
use Librinfo\EcommerceBundle\Entity\ProductVariant;
use Librinfo\EcommerceBundle\Entity\SalesJournalItem;

class OrderItemStrategy implements StrategyInterface
{
    /**
     * @var string
     */
    private $default = 'PRD';

    /**
     * @param OrderItemInterface $item
     *
     * @return string
     */
    public function getLabel($item): string
    {
        /** @var ProductVariant $variant */
        $variant = $item->getVariant();
        $itemIdentifier = $variant->getCode() ? $this->default . ' - ' . $variant->getCode() : $this->default;

        return $itemIdentifier;
    }

    /**
     * @param OrderItemInterface $orderItem
     */
    public function handleOperation(SalesJournalItem $salesJournalItem, $orderItem): void
    {
        $salesJournalItem->addCredit($orderItem->getSubTotal());
    }
}
