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

use Blast\Component\Resource\Repository\ResourceRepositoryInterface;
use Doctrine\Common\Collections\Collection;
use Sil\Component\Order\Model\OrderInterface;

interface OrderStateHistoryRepositoryInterface extends ResourceRepositoryInterface
{
    /**
     * Finds the complete state history of targeted order.
     *
     * @param OrderInterface $order
     *
     * @return Collection
     */
    public function findHistoryForOrder(OrderInterface $order): Collection;
}
