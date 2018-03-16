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
class Address implements ResourceInterface, AddressInterface
{
    /**
     * street.
     *
     * @var string
     */
    protected $street;

    /**
     * city.
     *
     * @var string
     */
    protected $city;

    /**
     * post code.
     *
     * @var string
     */
    protected $postCode;

    /**
     * province.
     *
     * @var string
     */
    protected $province;

    /**
     * country.
     *
     * @var string
     */
    protected $country;

    /**
     * other information.
     *
     * @var string
     */
    protected $other;

    /**
     * label.
     *
     * @var string
     */
    protected $label;

    /**
     * contact.
     *
     * @var Contact
     */
    protected $contact;

    /**
     * type.
     *
     * @var string
     */
    protected $type;

    /**
     * @param string $street
     * @param string $city
     * @param string $postcode
     * @param string $country
     */
    public function __construct(string $street, City $city, string $country)
    {
        $this->street = $street;
        $this->city = $city->getName();
        $this->postCode = $city->getPostCode();
        $this->country = $country;
    }

    /**
     * Get the value of street.
     *
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * Set the value of street.
     *
     * @param string $street
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * Get the value of city.
     *
     * @return City
     */
    public function getCity(): City
    {
        return new City($this->city, $this->postCode);
    }

    /**
     * Set the value of post code and city through a City instance.
     *
     * @param City $city
     */
    public function setCity(City $city): void
    {
        $this->city = $city->getName();
        $this->postCode = $city->getPostCode();
    }

    /**
     * Get the value of province.
     *
     * @return string
     */
    public function getProvince(): string
    {
        return $this->province;
    }

    /**
     * Set the value of province.
     *
     * @param string $province
     */
    public function setProvince(string $province): void
    {
        $this->province = $province;
    }

    /**
     * Get the value of country.
     *
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Set the value of country.
     *
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * Get the value of other information.
     *
     * @return string
     */
    public function getOther(): string
    {
        return $this->other;
    }

    /**
     * Set the value of other information.
     *
     * @param string $other
     */
    public function setOther(string $other): void
    {
        $this->other = $other;
    }

    /**
     * Get the value of contact.
     *
     * @return Contact|null
     */
    public function getContact(): ?Contact
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

    /**
     * Get the value of type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the value of type.
     *
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * Get the value of label.
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Set the value of label.
     *
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }
}
