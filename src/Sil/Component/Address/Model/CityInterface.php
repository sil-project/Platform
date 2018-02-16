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

interface CityInterface
{
    /**
     * Return the name of current city.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Gets city code.
     *
     * @return null|string
     */
    public function getCode(): ?string;

    /**
     * Sets the city code.
     *
     * @param string $code
     */
    public function setCode(string $code): void;

    /**
     * Gets the city's postcode.
     *
     * @return PostCodeInterface
     */
    public function getPostCode(): PostCodeInterface;

    /**
     * Gets the city's country.
     *
     * @return CountryInterface
     */
    public function getCountry(): CountryInterface;
}
