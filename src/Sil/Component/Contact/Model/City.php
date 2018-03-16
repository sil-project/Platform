<?php

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
class City implements ResourceInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $postCode;

    /**
     * @var Country
     */
    protected $country;

    /**
     * @var Province
     */
    protected $province;

    /**
     * @param string $name
     * @param string $postCode
     */
    public function __construct(string $name, string $postCode)
    {
        $this->name = $name;
        $this->postCode = $postCode;
    }

    /**
     * Get the value of Name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of Name.
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the value of Code.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Set the value of Code.
     *
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * Get the value of Post Code.
     *
     * @return string
     */
    public function getPostCode(): string
    {
        return $this->postCode;
    }

    /**
     * Set the value of Post Code.
     *
     * @param string $postCode
     */
    public function setPostCode(string $postCode): void
    {
        $this->postCode = $postCode;
    }

    /**
     * Get the value of Country.
     *
     * @return Country
     */
    public function getCountry(): ?Country
    {
        return $this->country;
    }

    /**
     * Set the value of Country.
     *
     * @param Country $country
     */
    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }

    /**
     * Get the value of Province.
     *
     * @return Province
     */
    public function getProvince(): ?Province
    {
        return $this->province;
    }

    /**
     * Set the value of Province.
     *
     * @param Province $province
     */
    public function setProvince(Province $province): void
    {
        $this->province = $province;
    }
}
