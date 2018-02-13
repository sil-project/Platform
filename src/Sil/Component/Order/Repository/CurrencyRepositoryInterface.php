<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Order\Repository;

use InvalidArgumentException;
use Blast\Component\Resource\Repository\ResourceRepositoryInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;

interface CurrencyRepositoryInterface extends ResourceRepositoryInterface
{
    /**
     * Retreive the currency for specific code.
     *
     * @return CurrencyInterface
     *
     * @throws InvalidArgumentException
     */
    public function getByCode(string $code): CurrencyInterface;
}
