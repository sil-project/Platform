<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\EcommerceBundle\Dashboard\Stats;

use Librinfo\EcommerceBundle\Entity\Order;
use Librinfo\EcommerceBundle\Entity\OrderInterface;

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
