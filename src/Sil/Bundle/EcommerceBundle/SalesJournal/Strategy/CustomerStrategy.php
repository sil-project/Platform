<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\SalesJournal\Strategy;

use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sil\Bundle\EcommerceBundle\Entity\Payment;
use Sil\Bundle\EcommerceBundle\Entity\SalesJournalItem;

class CustomerStrategy implements StrategyInterface
{
    /**
     * @var string
     */
    private $default = 'CUS';

    /**
     * @param CustomerInterface $payment
     *
     * @return string
     */
    public function getLabel($order): string
    {
        return (string) $this->default . ' - ' . $order->getCustomer();
    }

    /**
     * @param mixed $orderOrPayment
     */
    public function handleOperation(SalesJournalItem $salesJournalItem, $orderOrPayment): void
    {
        if ($orderOrPayment instanceof OrderInterface || $orderOrPayment instanceof OrderItemInterface) {
            $salesJournalItem->addDebit($orderOrPayment->getTotal());
        } elseif ($orderOrPayment instanceof Payment) {
            $salesJournalItem->addCredit($orderOrPayment->getAmount());
        }
    }
}
