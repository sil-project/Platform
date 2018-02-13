<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Invoice\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sil\Component\Invoice\Model\Currency;
use Sil\Component\Invoice\Model\CustomerInfo;
use Sil\Component\Invoice\Model\Invoice;
use Sil\Component\Invoice\Model\InvoiceAdjustment;
use Sil\Component\Invoice\Model\InvoiceAdjustmentType;
use Sil\Component\Invoice\Model\InvoiceItem;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 *
 * @coversDefaultClass \Sil\Component\Invoice\Model\Invoice
 */
class InvoiceTest extends TestCase
{
    /**
     * An exception should be thrown when adding an item
     * that is already in the invoice.
     */
    public function test_add_already_existing_item()
    {
        $invoice = $this->createInvoice();
        $item = new InvoiceItem('foo service', 100, 1, 0.20);

        $invoice->addItem($item);

        $this->expectException(\InvalidArgumentException::class);

        $invoice->addItem($item);
    }

    /**
     * An exception should be thrown when trying to remove an item that is not in the invoice.
     */
    public function test_remove_non_existing_item()
    {
        $invoice = $this->createInvoice();
        $item1 = new InvoiceItem('foo service', 40, 1, 0.20);
        $item2 = new InvoiceItem('bar product', 30, 2, 0.20);

        $invoice->addItem($item1);

        $this->expectException(\InvalidArgumentException::class);

        $invoice->removeItem($item2);
    }

    /**
     * An exception should be thrown when adding an adjustment
     * that is already in the invoice.
     */
    public function test_add_already_existing_adjustment()
    {
        $invoice = $this->createInvoice();
        $adjustment = new InvoiceAdjustment('christmas offer', InvoiceAdjustmentType::minus(), 5);

        $invoice->addAdjustment($adjustment);

        $this->expectException(\InvalidArgumentException::class);

        $invoice->addAdjustment($adjustment);
    }

    /**
     * An exception should be thrown when trying to remove an adjustment that
     * is not in the invoice.
     */
    public function test_remove_non_existing_adjustment()
    {
        $invoice = $this->createInvoice();
        $adjustment = new InvoiceAdjustment('christmas offer', InvoiceAdjustmentType::minus(), 5);
        $adjustment2 = new InvoiceAdjustment('new customer offer', InvoiceAdjustmentType::minus(), 10);

        $invoice->addAdjustment($adjustment);

        $this->expectException(\InvalidArgumentException::class);

        $invoice->removeAdjustment($adjustment2);
    }

    private function createInvoice()
    {
        return new Invoice(
            'FA01',
            new \DateTime(),
            new Currency('euro', 'eu', '€'),
            0.20,
            'Foo LLC, 2 bar street',
            new \DateTime(),
            20,
            100,
            120,
            0,
            120,
            'lorem ipsum dolor sit amet',
            new CustomerInfo('Bar Baz')
        );
    }
}
