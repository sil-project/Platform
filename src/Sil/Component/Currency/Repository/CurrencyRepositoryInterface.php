<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Currency\Repository;

use InvalidArgumentException;
use Sil\Component\Currency\Model\CurrencyInterface;

interface CurrencyRepositoryInterface
{
    /**
     * Returns the currency for targeted code.
     *
     * @param string $currencyCode
     *
     * @return CurrencyInterface
     *
     * @throws InvalidArgumentException
     */
    public function getByCode(string $currencyCode): CurrencyInterface;
}
