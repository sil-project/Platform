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
use Sil\Component\Emailing\Model\Recipient;

class RecipientTest extends TestCase
{
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = new Fixtures();
    }

    public function test_recipient_creation()
    {
        $emailAddress = new EmailAddress('recipient@sil.eu');

        $recipient = new Recipient($emailAddress);

        $this->assertEquals($emailAddress, $recipient->getEmail());
    }

    public function test_recipient_creation_from_valid_string()
    {
        $recipient = Recipient::createFromEmailAsString('valid.recipient@sil.eu');

        $this->assertEquals('valid.recipient@sil.eu', $recipient->getEmail()->getValue());
    }

    public function test_recipient_creation_from_invalid_string()
    {
        $this->expectException(\InvalidArgumentException::class);

        $recipient = Recipient::createFromEmailAsString('invalid.recipient');
    }
}
