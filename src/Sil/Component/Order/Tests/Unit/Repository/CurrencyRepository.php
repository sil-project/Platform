<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Order\Tests\Unit\Repository;

use InvalidArgumentException;
use Blast\Component\Resource\Repository\InMemoryRepository;
use Sil\Component\Order\Repository\CurrencyRepositoryInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;

class CurrencyRepository extends InMemoryRepository implements CurrencyRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getByCode(string $code): CurrencyInterface
    {
        $currency = $this->findOneBy(['code' => $code]);

        if ($currency === null) {
            throw new InvalidArgumentException(sprintf('Currency code %s is not found', $code));
        }

        return $currency;
    }
}
