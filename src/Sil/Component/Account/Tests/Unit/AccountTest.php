<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Account\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sil\Component\Account\Model\Account;
use Sil\Component\Contact\Model\Contact;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 *
 * @coversDefaultClass \Sil\Component\Account\Model\Account
 */
class AccountTest extends TestCase
{
    /**
     * The first contact added to the account should be set as the default contact.
     */
    public function testSetDefaultContactWhenAddingFirstContact()
    {
        $account = new Account('foo', 'bar');
        $contact = new Contact();

        $account->addContact($contact);

        $this->assertNotNull($account->getDefaultContact());
        $this->assertSame($contact, $account->getDefaultContact());
    }

    /**
     * An exception should be thrown when adding a contact
     * that is already associated to this account.
     */
    public function testAddAlreadyExistingContact()
    {
        $account = new Account('foo', 'bar');
        $contact = new Contact();

        $account->addContact($contact);

        $this->expectException(\InvalidArgumentException::class);

        $account->addContact($contact);
    }

    /**
     * The default contact should be unset when removing the only contact of an account.
     */
    public function testUnsetDefaultContactWhenRemovingOnlyContact()
    {
        $account = new Account('foo', 'bar');
        $contact = new Contact();

        $account->addContact($contact);

        $account->removeContact($contact);

        $this->assertNull($account->getDefaultContact());
    }

    /**
     * An exception should be thrown when trying to remove a contact that is not associated to the account.
     */
    public function testRemoveNonExistingContact()
    {
        $account = new Account('foo', 'bar');
        $contact1 = new Contact();
        $contact2 = new Contact();

        $account->addContact($contact1);

        $this->expectException(\InvalidArgumentException::class);

        $account->removeContact($contact2);
    }

    /**
     * When an account has several contacts,
     * a new default contact should be set when removing the current one.
     */
    public function testSetNewDefaultContactAfterRemoval()
    {
        $account = new Account('foo', 'bar');

        $contact1 = new Contact();
        $contact2 = new Contact();

        $account->addContact($contact1);
        $account->addContact($contact2);

        $account->removeContact($contact1);

        $this->assertNotNull($account->getDefaultContact());
        $this->assertSame($account->getDefaultContact(), $contact2);
    }
}
