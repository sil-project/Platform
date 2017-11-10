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

use Sil\Bundle\StockBundle\Domain\Repository\StockUnitRepositoryInterface;
use Blast\Bundle\ResourceBundle\Doctrine\ORM\Repository\ResourceRepository;
use Sil\Bundle\StockBundle\Domain\Entity\Movement;
use Sil\Bundle\StockBundle\Domain\Entity\StockItemInterface;
use Sil\Bundle\StockBundle\Domain\Entity\Location;
use Sil\Bundle\StockBundle\Domain\Entity\LocationType;
use Sil\Bundle\StockBundle\Domain\Entity\BatchInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of UomTypeRepository.
 *
 * @author glenn
 */
class StockUnitRepository extends ResourceRepository implements StockUnitRepositoryInterface
{
    /**
     * @param StockItemInterface  $item
     * @param BatchInterface|null $batch
     * @param array               $orderBy
     *
     * @return array|StockUnit[]
     */
    public function findAvailableByStockItem(StockItemInterface $item,
        ?BatchInterface $batch = null, array $orderBy = []): array
    {
        $qb = $this->createQueryBuilder('su');
        $this->filterByLocation($qb, null, LocationType::INTERNAL);
        $this->filterByAvailability($qb);
        $this->filterByStockItem($qb, $item, $batch);
        $this->addOrderBy($qb, $orderBy);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return array|StockUnit[]
     */
    public function findAvailable(): array
    {
        $qb = $this->createQueryBuilder('su');
        $this->filterByAvailability($qb);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param StockItemInterface  $item
     * @param BatchInterface|null $batch
     * @param array               $orderBy
     *
     * @return array|StockUnit[]
     */
    public function findAvailableByStockItemAndLocation(StockItemInterface $item,
        Location $location, ?BatchInterface $batch = null, array $orderBy = []): array
    {
        $qb = $this->createQueryBuilder('su');
        $this->filterByLocation($qb, $location, LocationType::INTERNAL);
        $this->filterByAvailability($qb);
        $this->filterByStockItem($qb, $item, $batch);
        $this->addOrderBy($qb, $orderBy);

        return $qb->getQuery()->getResult();
    }

    public function findAvailableForMovementReservation(Movement $mvt): array
    {
        $item = $mvt->getStockItem();
        $batch = $mvt->getBatch();
        $srcLoc = $mvt->getSrcLocation();
        $destLoc = $mvt->getDestLocation();
        $outStrategy = $item->getOutputStrategy();

        $qb = $this->createQueryBuilder('su');
        $this->filterByAvailability($qb);
        $this->filterByStockItem($qb, $item, $batch);
        $this->filterByLocation($qb, $srcLoc, LocationType::INTERNAL, [$destLoc]);
        $this->addOrderBy($qb, $outStrategy->getOrderBy());

        return $qb->getQuery()->getResult();
    }

    /**
     * @param StockItemInterface $item
     *
     * @return array|StockUnit[]
     */
    public function findReservedByStockItem(StockItemInterface $item,
        ?BatchInterface $batch = null): array
    {
        $qb = $this->createQueryBuilder('su');
        $this->filterByLocation($qb, null, LocationType::INTERNAL);
        $this->filterByUnavailability($qb);
        $this->filterByStockItem($qb, $item, $batch);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Movement $mvt
     *
     * @return array|StockUnit[]
     */
    public function findReservedByMovement(Movement $mvt): array
    {
        $qb = $this->createQueryBuilder('su');
        $this->filterByLocation($qb, null, LocationType::INTERNAL);
        $this->filterByMovement($qb, $mvt);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param StockItemInterface $item
     *
     * @return array|StockUnit[]
     */
    public function findByStockItem(StockItemInterface $item,
        ?BatchInterface $batch = null): array
    {
        $qb = $this->createQueryBuilder('su');
        $this->filterByLocation($qb, null, LocationType::INTERNAL);
        $this->filterByStockItem($qb, $item, $batch);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Location $location
     * @param array    $orderBy
     *
     * @return array
     */
    public function findByLocation(Location $location, array $orderBy = [],
        ?int $limit = null): array
    {
        $qb = $this->createQueryBuilder('su');
        $this->filterByLocation($qb, $location);
        $this->addOrderBy($qb, $orderBy);
        $this->addLimit($qb, $limit);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param StockItemInterface $item
     * @param Location           $location
     *
     * @return array|StockUnit[]
     */
    public function findByStockItemAndLocation(StockItemInterface $item,
        Location $location, ?BatchInterface $batch = null): array
    {
        $qb = $this->createQueryBuilder('su');
        $this->filterByStockItem($qb, $item, $batch);
        $this->filterByLocation($qb, $location);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param QueryBuilder      $qb
     * @param Location|null     $location
     * @param LocationType|null $locationType
     *
     * @return QueryBuilder
     */
    private function filterByLocation(QueryBuilder $qb,
        ?Location $location = null, ?string $locationType = null,
        array $excludedLocations = [])
    {
        if (null == $locationType && null == $location) {
            return $qb;
        }

        $alias = $qb->getAllAliases()[0];
        $qb
            ->leftJoin($alias . '.location', 'l');

        if (null !== $locationType) {
            $qb
                ->andWhere('l.typeValue = :locationType')
                ->setParameter('locationType', $locationType);
        }
        if (null !== $location) {
            $qb
                ->andWhere('l.treeLft >= :treeLeft')
                ->andWhere('l.treeRgt <= :treeRight')
                ->orderBy('l.treeLvl', 'DESC')
                ->setParameter('treeLeft', $location->getTreeLft())
                ->setParameter('treeRight', $location->getTreeRgt());
        }
        if (count($excludedLocations)) {
            $qb
                ->andWhere('l NOT IN (:locations)')
                ->setParameter('locations', $excludedLocations);
        }

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     */
    private function filterByAvailability(QueryBuilder $qb)
    {
        $alias = $qb->getAllAliases()[0];
        $qb->andWhere($alias . '.reservationMovement IS NULL');

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     */
    private function filterByUnavailability(QueryBuilder $qb)
    {
        $alias = $qb->getAllAliases()[0];
        $qb->andWhere($alias . '.reservationMovement IS NOT NULL');

        return $qb;
    }

    /**
     * @param QueryBuilder       $qb
     * @param StockItemInterface $item
     * @param BatchInterface     $batch
     *
     * @return QueryBuilder
     */
    private function filterByStockItem(QueryBuilder $qb, $item, $batch = null)
    {
        $alias = $qb->getAllAliases()[0];
        $qb
            ->andWhere($alias . '.stockItem = :item')
            ->setParameter('item', $item);

        if (null !== $batch) {
            $qb
                ->andWhere($alias . '.batch = :batch')
                ->setParameter('batch', $batch);
        }

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param Movement     $mvt
     *
     * @return QueryBuilder
     */
    private function filterByMovement(QueryBuilder $qb, Movement $mvt)
    {
        $alias = $qb->getAllAliases()[0];
        $qb
            ->andWhere($alias . '.reservationMovement = :mvt')
            ->setParameter('mvt', $mvt);

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param array        $orderBy
     *
     * @return QueryBuilder
     */
    private function addOrderBy(QueryBuilder $qb, array $orderBy = [])
    {
        if (count($orderBy)) {
            $alias = $qb->getAllAliases()[0];
            foreach ($orderBy as $sortCol => $order) {
                $qb->orderBy($alias . '.' . $sortCol, $order);
            }
        }

        return $qb;
    }

    private function addLimit(QueryBuilder $qb, ?int $limit = null)
    {
        if (null !== $limit) {
            $qb->setMaxResults($limit);
        }

        return $qb;
    }
}
