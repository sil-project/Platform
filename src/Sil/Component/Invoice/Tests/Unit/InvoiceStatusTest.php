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
use Sil\Component\Invoice\Exception\InvoiceStatusChangeException;
use Sil\Component\Invoice\Model\InvoiceStatus;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 *
 * @coversDefaultClass \Sil\Component\Invoice\Model\InvoiceStatus
 */
class InvoiceStatusTest extends TestCase
{
    /**
     * An exception should be thrown when trying to update the status from draft to paid.
     */
    public function test_change_from_draft_to_paid()
    {
        $status = new InvoiceStatus();

        $this->expectException(InvoiceStatusChangeException::class);

        $status->bePaid();
    }

    /**
     * An exception should be thrown when trying to update the status from paid back to validated.
     */
    public function test_change_from_paid_to_validated()
    {
        $status = new InvoiceStatus();

        $status->beValidated();
        $status->bePaid();

        $this->expectException(InvoiceStatusChangeException::class);

        $status->beValidated();
    }
}
