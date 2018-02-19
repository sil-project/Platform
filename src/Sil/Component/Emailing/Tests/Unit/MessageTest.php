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
use Sil\Component\Emailing\Model\GroupedMessage;
use Sil\Component\Emailing\Model\SimpleMessage;

class MessageTest extends TestCase
{
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = new Fixtures();
    }

    public function test_simple_email_creation()
    {
        $from = new EmailAddress('from@sil.eu');
        $to = new EmailAddress('to@sil.eu');
        $message = new SimpleMessage('Simple message', 'a simple message test', null, $from, $to);

        $this->assertEquals('Simple message', $message->getTitle());
        $this->assertEquals('a simple message test', $message->getContent());
        $this->assertEquals($from->getValue(), $message->getFrom()->getValue());
        $this->assertContains($to, $message->getTo());
    }

    public function test_grouped_message_creation()
    {
        $message = new GroupedMessage('Grouped message', 'a grouped message test');

        $this->assertEquals('Grouped message', $message->getTitle());
        $this->assertEquals('a grouped message test', $message->getContent());
    }
}
