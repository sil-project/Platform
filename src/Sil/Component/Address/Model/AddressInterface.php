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

interface AddressInterface
{
    /**
     * Return the street of current address.
     *
     * @return string
     */
    public function getStreet(): string;

    /**
     * Gets address complement.
     *
     * @return null|string
     */
    public function getOther(): ?string;

    /**
     * Sets the address complement.
     *
     * @param string $other
     */
    public function setOther(?string $other): void;

    /**
     * Gets the City of current address.
     *
     * @return CityInterface
     */
    public function getCity(): CityInterface;
}
