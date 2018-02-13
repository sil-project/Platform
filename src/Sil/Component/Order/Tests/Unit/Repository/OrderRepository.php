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

use Blast\Component\Resource\Repository\InMemoryRepository;
use Sil\Component\Order\Repository\OrderRepositoryInterface;
use Sil\Component\Order\Model\OrderInterface;

class OrderRepository extends InMemoryRepository implements OrderRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findLatestOrder(): ?OrderInterface
    {
        $result = $this->findBy([], ['createdAt' => InMemoryRepository::ORDER_DESCENDING], 1);

        if (count($result) === 0) {
            $result = null;
        } else {
            $result = $result[0];
        }

        return $result;
    }
}
