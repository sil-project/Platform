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

namespace Librinfo\EcommerceBundle\SalesJournal\Factory;

use Librinfo\EcommerceBundle\Entity\OrderInterface;
use Librinfo\EcommerceBundle\Entity\Invoice;
use Librinfo\EcommerceBundle\Entity\Payment;
use Librinfo\EcommerceBundle\Entity\SalesJournalItem;

class SalesJournalItemFactory
{
    public function newSalesJournalItem($label, ?OrderInterface $order = null, ?Invoice $invoice = null, ?Payment $payment = null)
    {
        $salesJournalItem = new SalesJournalItem();

        $salesJournalItem->setLabel($label);

        if ($order !== null) {
            $salesJournalItem->setOrder($order);
        }

        if ($invoice !== null) {
            $salesJournalItem->setInvoice($invoice);
        }

        if ($payment !== null) {
            $salesJournalItem->setPayment($payment);
        }

        return $salesJournalItem;
    }
}
