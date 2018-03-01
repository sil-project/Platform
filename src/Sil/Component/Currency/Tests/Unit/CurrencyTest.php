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

use DomainException;
use Locale;
use PHPUnit\Framework\TestCase;
use Sil\Component\Currency\Model\Currency;

class CurrencyTest extends TestCase
{
    public function setUp()
    {
        Locale::setDefault('en-US');
    }

    public function test_currency_construct()
    {
        $currency = new Currency('EUR');
    }

    public function test_currency_construct_with_not_managed_code()
    {
        $this->expectException(DomainException::class);

        $currency = new Currency('FAKE');
    }

    public function test_currency_get_name()
    {
        $currency = new Currency('USD');

        $this->assertEquals('US Dollar', $currency->getName());
    }

    public function test_currency_get_symbol()
    {
        $currency = new Currency('EUR');

        $this->assertEquals('€', $currency->getSymbol());
    }

    public function test_currency_get_code()
    {
        $currency = new Currency('EUR');

        $this->assertEquals('EUR', $currency->getCode());
    }

    public function test_currency_get_iso_code()
    {
        $currency = new Currency('EUR');

        $this->assertEquals(978, $currency->getIsoCode());
    }
}
