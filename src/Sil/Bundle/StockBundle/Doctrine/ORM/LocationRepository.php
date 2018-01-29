<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Doctrine\ORM;

use Sil\Component\Stock\Repository\LocationRepositoryInterface;
use Blast\Bundle\ResourceBundle\Doctrine\ORM\Repository\NestedTreeResourceRepository;
use Sil\Component\Stock\Model\Location;
use Sil\Component\Stock\Model\LocationType;
use Sil\Component\Stock\Model\StockItemInterface;

/**
 * @author glenn
 */
class LocationRepository extends NestedTreeResourceRepository implements LocationRepositoryInterface
{
    public function createQueryBuilder($alias, $indexBy = null)
    {
        $qb = parent::createQueryBuilder($alias, $indexBy);
        $qb->orderBy($alias . '.treeRoot', 'ASC')
            ->orderBy($alias . '.treeLft', 'ASC');

        return $qb;
    }

    public function findAll()
    {
        return $this->createQueryBuilder('o')->getQuery()->getResult();
    }

    public function findInternalLocations(): array
    {
        $qb = $this->createQueryBuilder('l')
            ->where('l.typeValue = :type')
            ->setParameter('type', LocationType::INTERNAL);

        return $qb->getQuery()->getResult();
    }

    public function findVirtualLocations(): array
    {
        $qb = $this->createQueryBuilder('l')
          ->where('l.typeValue = :type')
          ->setParameter('type', LocationType::VIRTUAL);

        return $qb->getQuery()->getResult();
    }

    public function findCustomerLocations(): array
    {
        $qb = $this->createQueryBuilder('l')
            ->where('l.typeValue = :type')
            ->setParameter('type', LocationType::CUSTOMER);

        return $qb->getQuery()->getResult();
    }

    public function findSupplierLocations(): array
    {
        $qb = $this->createQueryBuilder('l')
            ->where('l.typeValue = :type')
            ->setParameter('type', LocationType::SUPPLIER);

        return $qb->getQuery()->getResult();
    }

    public function findByOwnedItem(StockItemInterface $item,
        ?string $locationType = null): array
    {
        $qb = $this->createQueryBuilder('l')
            ->Join('Sil\Component\Stock\Model\StockUnit', 'su',
                'WITH', 'su.location = l.id')
            ->where('su.stockItem = :item')
            ->setParameter('item', $item);

        if (null !== $locationType) {
            $qb
                ->andWhere('l.typeValue = :locationType')
                ->setParameter('locationType', $locationType);
        }

        return $qb->getQuery()->getResult();
    }
}
