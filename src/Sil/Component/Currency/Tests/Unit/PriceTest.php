<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Currency\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sil\Component\Currency\Model\Currency;
use Sil\Component\Currency\Model\Price;

class PriceTest extends TestCase
{
    public function test_price_construct()
    {
        $currency = new Currency('EUR');

        $price = new Price($currency, 512.99);
    }

    public function test_price_to_string()
    {
        $currency = new Currency('EUR');

        $price = new Price($currency, 512.99);

        $this->assertEquals('512.99 €', (string) $price);
    }
}
