<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Doctrine\ORM;

use Sil\Bundle\StockBundle\Domain\Repository\StockItemRepositoryInterface;
use Blast\Bundle\ResourceBundle\Doctrine\ORM\Repository\ResourceRepository;
use Sil\Bundle\StockBundle\Domain\Entity\Location;

/**
 * Description of UomTypeRepository.
 *
 * @author glenn
 */
class StockItemRepository extends ResourceRepository implements StockItemRepositoryInterface
{
    public function findByLocation(Location $location)
    {
        $qb = $this->createQueryBuilder('si')
            ->leftJoin('Sil\Bundle\StockBundle\Domain\Entity\StockUnit', 'su',
                'WITH', 'su.stockItem = si.id')
            ->leftJoin('su.location', 'l')
            ->andWhere('l.treeLft >= :treeLeft')
            ->andWhere('l.treeRgt <= :treeRight')
            ->setParameter('treeLeft', $location->getTreeLft())
            ->setParameter('treeRight', $location->getTreeRgt());

        return $qb->getQuery()->getResult();
    }
}
