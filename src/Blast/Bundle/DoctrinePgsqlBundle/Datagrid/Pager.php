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

namespace Blast\Bundle\DoctrinePgsqlBundle\Datagrid;

use Doctrine\ORM\Query;
use Sonata\DoctrineORMAdminBundle\Datagrid\Pager as BasePager;

class Pager extends BasePager
{
    /**
     * {@inheritdoc}
     */
    public function computeNbResult()
    {
        $countQuery = clone $this->getQuery();

        if (count($this->getParameters()) > 0) {
            $countQuery->setParameters($this->getParameters());
        }

        $countQuery->select(sprintf('count(DISTINCT %s.%s) as cnt', $countQuery->getRootAlias(), current($this->getCountColumn())))
            ->resetDQLPart('orderBy');
        $query = $countQuery->getQuery();

        // Use ILIKE instead of LIKE for Postgresql
        if ('pdo_pgsql' == $countQuery->getEntityManager()->getConnection()->getDriver()->getName()) {
            $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Blast\Bundle\DoctrinePgsqlBundle\DoctrineExtensions\BlastWalker');
        }

        return $query->getSingleScalarResult();
    }
}
