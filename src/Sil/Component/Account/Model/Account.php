<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Account\Model;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Sil\Component\Contact\Model\ContactInterface;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class Account implements AccountInterface
{
    /**
     * name.
     *
     * @var string
     */
    protected $name;

    /**
     * code.
     *
     * @var string
     */
    protected $code;

    /**
     * default contact.
     *
     * @var ContactInterface
     */
    protected $defaultContact;

    /**
     * contacts.
     *
     * @var Collection|ContactInterface[]
     */
    protected $contacts;

    /**
     * @param string $name
     * @param string $code
     */
    public function __construct(string $name, string $code)
    {
        $this->name = $name;
        $this->code = $code;
        $this->contacts = new ArrayCollection();
    }

    /**
     * Get the value of name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name.
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the value of code.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Set the value of code.
     *
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * Get the value of default contact.
     *
     * @return ContactInterface|null
     */
    public function getDefaultContact(): ?ContactInterface
    {
        return $this->defaultContact;
    }

    /**
     * Set the value of default contact.
     *
     * @param ContactInterface $defaultContact
     */
    public function setDefaultContact(ContactInterface $defaultContact): void
    {
        $this->defaultContact = $defaultContact;
    }

    /**
     * Get the value of contacts.
     *
     * @return array|ContactInterface[]
     */
    public function getContacts(): array
    {
        return $this->contacts->toArray();
    }

    /**
     * Add a Contact to the collection.
     *
     * @param ContactInterface $contact
     */
    public function addContact(ContactInterface $contact)
    {
        if ($this->contacts->contains($contact)) {
            throw new \InvalidArgumentException('This contact is already associated to this account');
        }

        $this->contacts->add($contact);

        if (!$this->defaultContact) {
            $this->setDefaultContact($contact);
        }
    }

    /**
     *  Remove a Contact from the collection.
     *
     * @param ContactInterface $contact
     */
    public function removeContact(ContactInterface $contact)
    {
        if (!$this->hasContact($contact)) {
            throw new \InvalidArgumentException('Trying to remove a contact that does not belong to this account');
        }

        $this->contacts->removeElement($contact);

        if ($contact === $this->defaultContact) {
            if ($this->contacts->count() > 0) {
                $this->defaultContact = $this->contacts->first();

                return;
            }

            $this->defaultContact = null;
        }
    }

    /**
     * Check if a Contact exists in the Collection.
     *
     * @param ContactInterface $contact
     *
     * @return bool wether the contact exists
     */
    public function hasContact(ContactInterface $contact)
    {
        return $this->contacts->contains($contact);
    }
}
