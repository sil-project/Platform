<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Admin;

use Blast\Bundle\CoreBundle\Admin\CoreAdmin;

class SearchIndexEntityAdmin extends CoreAdmin
{
    public static function searchIndexCallback($admin, $property, $value)
    {
        $searchIndex = $admin->getClass() . 'SearchIndex';
        $datagrid = $admin->getDatagrid();
        $queryBuilder = $datagrid->getQuery();
        $alias = $queryBuilder->getRootalias();

        $queryBuilder
            ->leftJoin($searchIndex, 's', 'WITH', $alias . '.id = s.object')
            ->where('s.keyword LIKE :value')
            ->setParameter('value', "%$value%")
        ;
    }
}
