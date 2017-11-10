<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\SalesJournal\Strategy;

use Sylius\Component\Core\Model\PaymentInterface;
use Sil\Bundle\EcommerceBundle\Entity\SalesJournalItem;

class PaymentStrategy implements StrategyInterface
{
    /**
     * @var string
     */
    private $default = 'PAY';

    /**
     * @param PaymentInterface $payment
     *
     * @return string
     */
    public function getLabel($payment): string
    {
        $adjustmentIdentifier = $this->default . ' - ' . $payment->getMethod()->getCode();

        return $adjustmentIdentifier;
    }

    /**
     * @param PaymentInterface $payment
     */
    public function handleOperation(SalesJournalItem $salesJournalItem, $payment): void
    {
        $salesJournalItem->addDebit($payment->getAmount());
    }
}
