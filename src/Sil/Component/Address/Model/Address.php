<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Address\Model;

class Address implements AddressInterface
{
    /**
     * The street name, number, district, etc.
     *
     * @var string
     */
    protected $street;

    /**
     * Any other information that is usefull for the address.
     *
     * @var string
     */
    protected $other;

    /**
     * City.
     *
     * @var CityInterface
     */
    protected $city;

    public function __construct(string $street, CityInterface $city)
    {
        $this->street = $street;
        $this->city = $city;
    }

    /**
     * {@inheritdoc}
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * {@inheritdoc}
     */
    public function getOther(): ?string
    {
        return $this->other;
    }

    /**
     * {@inheritdoc}
     */
    public function setOther(?string $other): void
    {
        $this->other = $other;
    }

    /**
     * {@inheritdoc}
     */
    public function getCity(): CityInterface
    {
        return $this->city;
    }
}
