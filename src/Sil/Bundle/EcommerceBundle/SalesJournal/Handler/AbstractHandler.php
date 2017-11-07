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

namespace Librinfo\EcommerceBundle\SalesJournal\Handler;

use Librinfo\EcommerceBundle\SalesJournal\Factory\SalesJournalItemFactory;
use Librinfo\EcommerceBundle\Repository\SalesJournalItemRepository;

abstract class AbstractHandler
{
    /**
     * @var array
     */
    protected $items;

    /**
     * @var SalesJournalItemFactory
     */
    protected $salesJournalItemFactory;

    /**
     * @var SalesJournalItemRepository
     */
    protected $salesJournalItemRepository;

    protected function handleSalesJournalItem($label, $order = null, $invoice = null, $payment = null)
    {
        if (array_key_exists($label, $this->items)) {
            $salesJournalItem = $this->items[$label];
        } else {
            $salesJournalItem = $this->salesJournalItemFactory->newSalesJournalItem($label, $order, $invoice);
            $this->items[$label] = $salesJournalItem;
        }

        return $salesJournalItem;
    }

    /**
     * @param SalesJournalItemFactory salesJournalItemFactory
     */
    public function setSalesJournalItemFactory(SalesJournalItemFactory $salesJournalItemFactory): void
    {
        $this->salesJournalItemFactory = $salesJournalItemFactory;
    }

    /**
     * @param SalesJournalItemRepository $salesJournalItemRepository
     */
    public function setSalesJournalItemRepository(SalesJournalItemRepository $salesJournalItemRepository): void
    {
        $this->salesJournalItemRepository = $salesJournalItemRepository;
    }
}
