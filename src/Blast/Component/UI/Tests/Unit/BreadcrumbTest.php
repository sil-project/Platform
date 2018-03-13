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
use Blast\Component\UI\Model\Breadcrumb;
use Blast\Component\UI\Model\BreadcrumbItem;

class BreadcrumbTest extends TestCase
{
    public function test_last_inserted_item_is_current()
    {
        $breadcrumb = new Breadcrumb();

        $item1 = new BreadcrumbItem($breadcrumb, 'item #1');
        $this->assertTrue($item1->isCurrent());

        $item2 = new BreadcrumbItem($breadcrumb, 'item #2');
        $this->assertTrue($item2->isCurrent());

        $item3 = new BreadcrumbItem($breadcrumb, 'item #3');
        $this->assertTrue($item3->isCurrent());

        $this->assertFalse($item1->isCurrent());
        $this->assertFalse($item2->isCurrent());
    }

    public function test_last_item_is_current_after_removing_current()
    {
        $breadcrumb = new Breadcrumb();

        $item1 = new BreadcrumbItem($breadcrumb, 'item #1');
        $this->assertTrue($item1->isCurrent());

        $item2 = new BreadcrumbItem($breadcrumb, 'item #2');
        $this->assertTrue($item2->isCurrent());

        $item3 = new BreadcrumbItem($breadcrumb, 'item #3');
        $this->assertTrue($item3->isCurrent());

        $this->assertFalse($item1->isCurrent());
        $this->assertFalse($item2->isCurrent());

        $breadcrumb->removeItem($item3);

        $this->assertFalse($item1->isCurrent());
        $this->assertTrue($item2->isCurrent());
    }
}
