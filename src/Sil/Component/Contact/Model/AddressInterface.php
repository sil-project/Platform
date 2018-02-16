<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Contact\Model;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
interface AddressInterface
{
    /**
     * Get the value of street.
     *
     * @return string
     */
    public function getStreet(): string;

    /**
     * Get the value of city.
     *
     * @return City
     */
    public function getCity(): City;

    /**
     * Get the value of country.
     *
     * @return string
     */
    public function getCountry(): string;
}
