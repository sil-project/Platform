<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Dashboard\Stats;

use Sil\Bundle\EcommerceBundle\Entity\Order;
use Sil\Bundle\EcommerceBundle\Entity\OrderInterface;

class OrdersToProcess extends AbstractStats
{
    public function getData(array $parameters = []): array
    {
        $orderRepo = $this->doctrine->getRepository(Order::class);

        $query = $orderRepo->createQueryBuilder('o');
        $query
            ->where('o.state = :pendingState')
            ->orderBy('o.checkoutCompletedAt', 'DESC');

        $query->setParameter('pendingState', OrderInterface::STATE_NEW);

        $query->setMaxResults(5);

        return $query->getQuery()->getResult();
    }
}
