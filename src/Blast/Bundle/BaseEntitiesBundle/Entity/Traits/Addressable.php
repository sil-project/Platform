<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Entity\Traits;

trait Addressable
{
    use Nameable;

    /**
     * @var string
     */
    protected $address;

    /**
     * @var string
     */
    protected $zip;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var bool
     */
    protected $npai = false;

    /**
     * @var string
     */
    protected $vcardUid;

    /**
     * @var bool
     */
    protected $confirmed = true;

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return Addressable
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param string $zip
     *
     * @return Addressable
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return Addressable
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return Addressable
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return bool
     */
    public function isNpai()
    {
        return $this->npai;
    }

    /**
     * @param bool $npai
     *
     * @return Addressable
     */
    public function setNpai($npai)
    {
        $this->npai = $npai;

        return $this;
    }

    /**
     * @return string
     */
    public function getVcardUid()
    {
        return $this->vcardUid;
    }

    /**
     * @param string $vcardUid
     *
     * @return Addressable
     */
    public function setVcardUid($vcardUid)
    {
        $this->vcardUid = $vcardUid;

        return $this;
    }

    /**
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @param bool $confirmed
     *
     * @return Addressable
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    /**
     * @param string $separator
     *
     * @return string
     */
    public function getFulltextAddress($separator = "\n")
    {
        $elems = [];
        if ($this->address) {
            $elems[] = $this->address;
        }
        $zip_city = [];
        if ($this->zip) {
            $zip_city[] = $this->zip;
        }
        if ($this->city) {
            $zip_city[] = $this->city;
        }

        if ($zip_city) {
            $elems[] = implode(' ', $zip_city);
        }
        if ($this->country) {
            $elems[] = $this->country;
        }

        return implode($separator, $elems);
    }
}
