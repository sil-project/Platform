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

namespace Sil\Bundle\StockBundle\Test\Unit;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class OperationTest extends AbstractStockTestCase
{
    public function testOperationLifecycle()
    {
        $op = $this->opService->createDraft();

        $this->assertTrue($op->isDraft());

        $this->opService->confirm($op);
        $this->assertTrue($op->isConfirmed());

        $this->opService->reserveUnits($op);
        $this->assertTrue($op->isFullyReserved() && $op->isAvailable());

        $this->opService->apply($op);
        $this->assertTrue($op->isDone());

        $this->expectException(\DomainException::class);
        $this->opService->cancel($op);
    }
}
