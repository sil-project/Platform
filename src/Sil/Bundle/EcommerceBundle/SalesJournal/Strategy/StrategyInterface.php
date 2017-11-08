<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\SalesJournal\Strategy;

use Sil\Bundle\EcommerceBundle\Entity\SalesJournalItem;

interface StrategyInterface
{
    /**
     * Guess the SalesJournalItem's label corresponding to element.
     *
     * @param mixed $item
     *
     * @return string
     */
    public function getLabel($item): string;

    /**
     * Apply the right operation (debit or credit) on the SalesJournalItem according to a specific strategy.
     *
     * @param SalesJournalItem $salesJournalItem
     * @param mixexd           $item
     */
    public function handleOperation(SalesJournalItem $salesJournalItem, $item): void;
}
