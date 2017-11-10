<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Doctrine\ORM;

use Sil\Bundle\StockBundle\Domain\Repository\LocationRepositoryInterface;
use Blast\Bundle\ResourceBundle\Doctrine\ORM\Repository\ResourceRepository;
use Sil\Bundle\StockBundle\Domain\Entity\Location;
use Sil\Bundle\StockBundle\Domain\Entity\LocationType;
use Sil\Bundle\StockBundle\Domain\Entity\StockItemInterface;

/**
 * @author glenn
 */
class LocationRepository extends ResourceRepository implements LocationRepositoryInterface
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
            ->Join('Sil\Bundle\StockBundle\Domain\Entity\StockUnit', 'su',
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
