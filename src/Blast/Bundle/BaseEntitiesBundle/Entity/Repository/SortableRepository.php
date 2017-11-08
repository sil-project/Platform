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

namespace Blast\Bundle\BaseEntitiesBundle\Entity\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityRepository;

class SortableRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        $this->_entityName = $class->name;
        $this->_em = $em;
        $this->_class = $class;
    }

    public function getMaxPosition()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('MAX(n.sortRank)')
           ->from($this->_entityName, 'n');
        $query = $qb->getQuery();
        $query->useQueryCache(false);
        $query->useResultCache(false);
        $res = $query->getResult();

        return $res[0][1];
    }

    /**
     * Move an object after a given sort rank
     * (add 1024 to the rank if the object is moved beyond the highest existing rank).
     *
     * @param string $id         Id of the object to move
     * @param int    $after_rank
     *
     * @return int | false  the new rank or false if the object could not be moved
     */
    public function moveObjectAfter($id, $after_rank)
    {
        $object = $this->find($id);
        if (!$object) {
            return false;
        }
        $old_rank = $object->getSortRank();

        $qb = $this->_em->createQueryBuilder()
            ->select('MIN(n.sortRank)')
            ->from($this->_entityName, 'n')
            ->where('n.sortRank > :rank')
            ->setParameter('rank', $after_rank);
        $query = $qb->getQuery();
        $res = $query->getResult();

        $before_rank = empty($res[0][1]) ? $after_rank + 2048 : $res[0][1];

        if ($old_rank > $after_rank && $old_rank < $before_rank) {
            return $old_rank;
        }

        $new_rank = $after_rank + ($before_rank - $after_rank) / 2;

        $object->setSortRank($new_rank);
        $this->_em->persist($object);
        $this->_em->flush();

        return $new_rank;
    }

    /**
     * Move an object before a given sort rank.
     *
     * @param string $id          Id of the object to move
     * @param int    $before_rank
     *
     * @return int | false  the new rank or false if the object could not be moved
     */
    public function moveObjectBefore($id, $before_rank)
    {
        $object = $this->find($id);
        if (!$object) {
            return false;
        }
        $old_rank = $object->getSortRank();

        $qb = $this->_em->createQueryBuilder()
            ->select('MAX(n.sortRank)')
            ->from($this->_entityName, 'n')
            ->where('n.sortRank < :rank')
            ->setParameter('rank', $before_rank);
        $query = $qb->getQuery();
        $res = $query->getResult();

        $after_rank = empty($res[0][1]) ? 0 : $res[0][1];

        if ($old_rank > $after_rank && $old_rank < $before_rank) {
            return $old_rank;
        }

        $new_rank = $after_rank + ($before_rank - $after_rank) / 2;

        $object->setSortRank($new_rank);
        $this->_em->persist($object);
        $this->_em->flush();

        return $new_rank;
    }
}
