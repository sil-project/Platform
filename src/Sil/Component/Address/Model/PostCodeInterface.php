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

use InvalidArgumentException;
use Blast\Component\Resource\Model\ResourceInterface;

interface PostCodeInterface extends ResourceInterface
{
    /**
     * Gets post code.
     *
     * @return string
     */
    public function getCode(): string;

    /**
     * @return array|CityInterface[]
     */
    public function getCities(): array;

    /**
     * @param CityInterface $city
     *
     * @throws InvalidArgumentException
     */
    public function addCity(CityInterface $city): void;

    /**
     * @param CityInterface $city
     *
     * @throws InvalidArgumentException
     */
    public function removeCity(CityInterface $city): void;
}
