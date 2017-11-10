<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UtilsBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Blast\Bundle\CoreBundle\Model\UserInterface;
use Blast\Bundle\UtilsBundle\Entity\CustomFilter;

class CustomFilterRepository extends EntityRepository
{
    /**
     * Create and persists a new CustomFilter.
     *
     * @param string              $filterName
     * @param string              $routeName
     * @param array               $routeParameters
     * @param array               $filterParameters
     * @param mixed|UserInterface $user
     *
     * @return CustomFilter
     */
    public function createNewCustomFilter($filterName, $routeName, $routeParameters, $filterParameters, $user = null)
    {
        $newFilter = new CustomFilter();
        $newFilter
            ->setName($filterName)
            ->setRouteName($routeName)
            ->setRouteParameters(json_encode($routeParameters, JSON_UNESCAPED_SLASHES))
            ->setFilterParameters(json_encode($filterParameters, JSON_UNESCAPED_SLASHES))
            ->setUser($user)
        ;

        $this->_em->persist($newFilter);
        $this->_em->flush($newFilter);

        return $newFilter;
    }

    public function findUserCustomFilters($routeName, $user = null)
    {
        return $this->findBy([
            'user'      => $user,
            'routeName' => $routeName,
        ]);
    }

    public function findGlobalFilters($routeName)
    {
        return $this->findUserCustomFilters($routeName);
    }

    public function findCurrentFilter($routeName, $filterName)
    {
        $qb = $this->createQueryBuilder('f');

        $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('f.routeName', ':routeName'),
                    $qb->expr()->eq('f.name', ':filterName')
                )
            )
            ->orderBy('f.user', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1);

        $qb
            ->setParameters([
                'routeName'  => $routeName,
                'filterName' => $filterName,
            ]);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
