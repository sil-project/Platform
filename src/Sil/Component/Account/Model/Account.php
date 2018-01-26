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
     * @return ContactInterface
     */
    public function getDefaultContact(): ContactInterface
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
     * @return Collection|ContactInterface[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }
}
