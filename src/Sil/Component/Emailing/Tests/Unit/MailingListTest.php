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
use Sil\Component\Emailing\Model\MailingList;
use Sil\Component\Emailing\Model\Recipient;

class MailingListTest extends TestCase
{
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = new Fixtures();
    }

    public function test_mailing_list_creation()
    {
        $mailingList = new MailingList('Test list', 'a test mailing list');

        $this->assertEquals('Test list', $mailingList->getName());
        $this->assertEquals('a test mailing list', $mailingList->getDescription());
        $this->assertFalse($mailingList->isEnabled());
    }

    public function test_mailing_list_enabling_without_recipients()
    {
        $mailingList = new MailingList('Test list', 'a test mailing list');

        $this->expectException(\DomainException::class);

        $mailingList->setEnabled(true);
    }

    public function test_mailing_list_enabling_with_recipients()
    {
        $mailingList = new MailingList('Test list', 'a test mailing list');

        $mailingList->addRecipient(Recipient::createFromEmailAsString('recipient@sil.eu'));

        $mailingList->setEnabled(true);
    }
}
