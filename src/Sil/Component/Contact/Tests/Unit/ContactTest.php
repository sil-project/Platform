<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Contact\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sil\Component\Contact\Model\Contact;
use Sil\Component\Contact\Model\Address;
use Sil\Component\Contact\Model\Phone;
use Sil\Component\Contact\Model\City;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 *
 * @coversDefaultClass \Sil\Component\Contact\Model\Contact
 */
class ContactTest extends TestCase
{
    /**
     * The first address added to the contact should be set as the default address.
     */
    public function test_set_default_address_when_adding_first_address()
    {
        $contact = new Contact();
        $address = new Address('foo street', new City('foo city', '55000'), 'foo country');

        $contact->addAddress($address);

        $this->assertNotNull($contact->getDefaultAddress());
        $this->assertSame($address, $contact->getDefaultAddress());
    }

    /**
     * An exception should be thrown when adding an address
     * that is already associated to this contact.
     */
    public function test_add_already_existing_address()
    {
        $contact = new Contact();
        $address = new Address('foo street', new City('foo city', '55000'), 'foo country');

        $contact->addAddress($address);

        $this->expectException(\InvalidArgumentException::class);

        $contact->addAddress($address);
    }

    /**
     * The deault address should be unset when removing the only address of a contact.
     */
    public function test_unset_default_address_when_removing_only_address()
    {
        $contact = new Contact();
        $address = new Address('foo street', new City('foo city', '55000'), 'foo country');

        $contact->addAddress($address);

        $contact->removeAddress($address);

        $this->assertNull($contact->getDefaultAddress());
    }

    /**
     * An exception should be thrown when trying to remove an address that is not associated to the contact.
     */
    public function test_remove_non_existing_address()
    {
        $contact = new Contact();
        $address1 = new Address('foo street', new City('foo city', '55000'), 'foo country');
        $address2 = new Address('bar street', new City('bar city', '44000'), 'bar country');

        $contact->addAddress($address1);

        $this->expectException(\InvalidArgumentException::class);

        $contact->removeAddress($address2);
    }

    /**
     * When a contact has several addresses,
     * a new default address should be set when removing the current one.
     */
    public function test_set_new_default_address_after_removal()
    {
        $contact = new Contact();

        $address1 = new Address('foo street', new City('foo city', '55000'), 'foo country');
        $address2 = new Address('bar street', new City('bar city', '44000'), 'bar country');

        $contact->addAddress($address1);
        $contact->addAddress($address2);

        $contact->removeAddress($address1);

        $this->assertNotNull($contact->getDefaultAddress());
        $this->assertSame($contact->getDefaultAddress(), $address2);
    }

    /**
     * The first phone added to the contact should be set as the default phone.
     */
    public function test_set_default_phone_when_adding_first_phone()
    {
        $contact = new Contact();
        $phone = new Phone('0123456789');

        $contact->addPhone($phone);

        $this->assertNotNull($contact->getDefaultPhone());
        $this->assertSame($phone, $contact->getDefaultPhone());
    }

    /**
     * An exception should be thrown when adding a phone
     * that is already associated to this contact.
     */
    public function test_add_already_existing_phone()
    {
        $contact = new Contact();
        $phone = new phone('0123456789');

        $contact->addPhone($phone);

        $this->expectException(\InvalidArgumentException::class);

        $contact->addPhone($phone);
    }

    /**
     * The deault address should be unset when removing the only address of a contact.
     */
    public function test_remove_only_phone()
    {
        $contact = new Contact();
        $phone = new Phone('0123456789');

        $contact->addPhone($phone);
        $contact->removePhone($phone);

        $this->assertNull($contact->getDefaultPhone());
    }

    /**
     * An exception should be thrown when trying to remove a phone that is not associated to the contact.
     */
    public function test_remove_non_existing_phone()
    {
        $contact = new Contact();
        $phone1 = new Phone('0123456789');
        $phone2 = new Phone('9876543210');

        $contact->addPhone($phone1);

        $this->expectException(\InvalidArgumentException::class);

        $contact->removePhone($phone2);
    }

    /**
     * When a contact has several phones,
     * a new default phone should be set when removing the current one.
     */
    public function test_set_new_default_phone_after_removal()
    {
        $contact = new Contact();
        $phone1 = new Phone('0123456789');
        $phone2 = new Phone('9876543210');

        $contact->addPhone($phone1);
        $contact->addPhone($phone2);

        $contact->removePhone($phone1);

        $this->assertNotNull($contact->getDefaultPhone());
        $this->assertSame($contact->getDefaultPhone(), $phone2);
    }
}
