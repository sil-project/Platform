<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Address\Repository;

use InvalidArgumentException;
use Blast\Component\Resource\Repository\ResourceRepositoryInterface;
use Doctrine\Common\Collections\Collection;
use Sil\Component\Address\Model\CountryInterface;

interface CountryRepositoryInterface extends ResourceRepositoryInterface
{
    /**
     * Retreive the country for specific code.
     *
     * @param string $code
     *
     * @return CountryInterface
     *
     * @throws InvalidArgumentException
     */
    public function getByCode(string $code): CountryInterface;

    /**
     * Retreive the country for given name.
     *
     * @param string $name
     *
     * @return CountryInterface
     */
    public function findByName(string $name): Collection;
}
