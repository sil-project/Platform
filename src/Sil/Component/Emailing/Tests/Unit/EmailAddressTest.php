<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Emailing\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sil\Component\Emailing\Tests\Unit\Fixtures\Fixtures;
use Sil\Component\Emailing\Model\EmailAddress;

class EmailAddressTest extends TestCase
{
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = new Fixtures();
    }

    public function test_valid_email_address_creation()
    {
        $emailAddress = new EmailAddress('valid@sil.eu');

        $this->assertTrue($emailAddress->isValid());
    }

    public function test_invalid_email_address_creation()
    {
        $this->expectException(\InvalidArgumentException::class);

        $emailAddress = new EmailAddress('not.a.valid.address');
    }
}
