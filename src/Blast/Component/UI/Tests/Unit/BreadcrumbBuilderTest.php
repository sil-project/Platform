<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Blast\Component\UI\Service\BreadcrumbBuilder;

class BreadcrumbBuilderTest extends TestCase
{
    public function test_last_inserted_item_is_current_with_handler()
    {
        $breadcrumbBuilder = new BreadcrumbBuilder();

        $breadcrumbBuilder
            ->addItem('item #1')
            ->addItem('item #2')
            ->addItem('item #3')
        ;

        $breadcrumb = $breadcrumbBuilder->getBreadcrumb();

        $items = $breadcrumb->getItems();

        for ($i = 0; $i < count($items); $i++) {
            if ($i == 2) {
                $this->assertTrue($items[$i]->isCurrent());
            } else {
                $this->assertFalse($items[$i]->isCurrent());
            }
        }
    }
}
