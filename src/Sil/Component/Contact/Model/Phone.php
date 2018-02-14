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

use Blast\Component\Resource\Model\ResourceInterface;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class Phone implements ResourceInterface, PhoneInterface
{
    /**
     * number.
     *
     * @var string
     */
    protected $number;

    /**
     * phone type.
     *
     * @var string
     */
    protected $type;

    /**
     * contact.
     *
     * @var Contact
     */
    protected $contact;

    /**
     * @param string $number
     */
    public function __construct(string $number)
    {
        $this->number = $number;
    }

    /**
     * Get the value of number.
     *
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Set the value of number.
     *
     * @param string number
     */
    public function setNumber($number): void
    {
        $this->number = $number;
    }

    /**
     * Get the value of phone type.
     *
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Set the value of phone type.
     *
     * @param string type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * Get the value of contact.
     *
     * @return Contact
     */
    public function getContact(): Contact
    {
        return $this->contact;
    }

    /**
     * Set the value of contact.
     *
     * @param Contact $contact
     */
    public function setContact(Contact $contact): void
    {
        $this->contact = $contact;
    }
}
