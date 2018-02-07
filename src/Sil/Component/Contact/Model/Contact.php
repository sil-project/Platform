<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Contact\Model;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Blast\Component\Resource\Model\ResourceInterface;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class Contact implements ResourceInterface, ContactInterface, GroupMemberInterface
{
    /**
     * first name.
     *
     * @var string
     */
    protected $firstName;

    /**
     * last name.
     *
     * @var string
     */
    protected $lastName;

    /**
     * title.
     *
     * @var string
     */
    protected $title;

    /**
     * email address.
     *
     * @var string
     */
    protected $email;

    /**
     * position.
     *
     * @var string
     */
    protected $position;

    /**
     * default address.
     *
     * @var AddressInterface
     */
    protected $defaultAddress;

    /**
     * addresses.
     *
     * @var Collection|AddressInterface[]
     */
    protected $addresses;

    /**
     * default phone.
     *
     * @var PhoneInterface
     */
    protected $defaultPhone;

    /**
     * phones.
     *
     * @var Collection|PhoneInterface[]
     */
    protected $phones;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->phones = new ArrayCollection();
    }

    /**
     * Get the value of first name.
     *
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Set the value of first name.
     *
     * @param string firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * Get the value of last name.
     *
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Set the value of last name.
     *
     * @param string lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * Get the value of title.
     *
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the value of title.
     *
     * @param string title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Get the value of email address.
     *
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the value of email address.
     *
     * @param string email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Get the value of position.
     *
     * @return string
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * Set the value of position.
     *
     * @param string position
     */
    public function setPosition(string $position): void
    {
        $this->position = $position;
    }

    /**
     * Get the value of default address.
     *
     * @return AddressInterface|null
     */
    public function getDefaultAddress(): ?AddressInterface
    {
        return $this->defaultAddress;
    }

    /**
     * Set the value of default address.
     *
     * @param AddressInterface $defaultAddress
     */
    public function setDefaultAddress(AddressInterface $defaultAddress): void
    {
        $this->defaultAddress = $defaultAddress;
    }

    /**
     * Get the value of addresses.
     *
     * @return array|AddressInterface[]
     */
    public function getAddresses(): array
    {
        return $this->addresses->toArray();
    }

    /**
     * Add an Address to the collection.
     *
     * @param AddressInterface $address
     */
    public function addAddress(AddressInterface $address)
    {
        if ($this->addresses->contains($address)) {
            throw new \InvalidArgumentException('This contact already owns this address');
        }

        $this->addresses->add($address);

        if (!$this->defaultAddress) {
            $this->setDefaultAddress($address);
        }
    }

    /**
     *  Remove an Address from the collection.
     *
     * @param AddressInterface $address
     */
    public function removeAddress(AddressInterface $address)
    {
        if (!$this->hasAddress($address)) {
            throw new \InvalidArgumentException('Trying to remove an address that does not belong to this contact');
        }

        $this->addresses->removeElement($address);

        if ($address === $this->defaultAddress) {
            if ($this->addresses->count() > 0) {
                $this->defaultAddress = $this->addresses->first();

                return;
            }

            $this->defaultAddress = null;
        }
    }

    /**
     * Check if an Address exists in the Collection.
     *
     * @param AddressInterface $address
     *
     * @return bool whether the Address exists
     */
    public function hasAddress(AddressInterface $address)
    {
        return $this->addresses->contains($address);
    }

    /**
     * Get the value of default phone.
     *
     * @return PhoneInterface|null
     */
    public function getDefaultPhone(): ?PhoneInterface
    {
        return $this->defaultPhone;
    }

    /**
     * Set the value of default phone.
     *
     * @param PhoneInterface $defaultPhone
     */
    public function setDefaultPhone(PhoneInterface $defaultPhone): void
    {
        $this->defaultPhone = $defaultPhone;
    }

    /**
     * Get the value of phones.
     *
     * @return array|PhoneInterface[]
     */
    public function getPhones(): array
    {
        return $this->phones->toArray();
    }

    /**
     * Add a Phone to the collection.
     *
     * @param PhoneInterface $phone
     */
    public function addPhone(PhoneInterface $phone)
    {
        if ($this->phones->contains($phone)) {
            throw new \InvalidArgumentException('This contact already owns this phone');
        }

        $this->phones->add($phone);

        if (!$this->defaultPhone) {
            $this->setDefaultPhone($phone);
        }
    }

    /**
     *  Remove a Phone from the collection.
     *
     * @param PhoneInterface $phone
     */
    public function removePhone(PhoneInterface $phone)
    {
        if (!$this->hasPhone($phone)) {
            throw new \InvalidArgumentException('Trying to remove a phone that does not belong to this contact');
        }

        $this->phones->removeElement($phone);

        if ($phone === $this->defaultPhone) {
            if ($this->phones->count() > 0) {
                $this->defaultPhone = $this->phones->first();

                return;
            }

            $this->defaultPhone = null;
        }
    }

    /**
     * Check if a Phone exists in the Collection.
     *
     * @param PhoneInterface $phone
     *
     * @return bool whether the Phone exists
     */
    public function hasPhone(PhoneInterface $phone)
    {
        return $this->phones->contains($phone);
    }
}
