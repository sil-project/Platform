<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PlatformBundle\Repository;

use Sil\Component\Stock\Repository\PartnerRepositoryInterface;
use Sil\Bundle\CRMBundle\Entity\Repository\OrganismRepository as BaseOrganismRepository;

class OrganismRepository extends BaseOrganismRepository implements PartnerRepositoryInterface
{
    public function getCustomers(): array
    {
        return $this->createQueryBuilder('c')
          ->where('c.isCustomer = :isCustomer')
          ->setParameter('isCustomer', true)
          ->getQuery()->getResult();
    }

    public function getSuppliers(): array
    {
        return $this->createQueryBuilder('c')
          ->where('c.isSupplier = :isSupplier')
          ->setParameter('isSupplier', true)
          ->getQuery()->getResult();
    }
}
