<?php

/*
 * This file is part of the Lisem Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\Admin;

use Sil\Bundle\CRMBundle\Admin\CircleAdmin as BaseCircleAdmin;

class CircleAdmin extends BaseCircleAdmin
{
    public static function groupedCirclesChoices($em, $entityClass)
    {
        $q = parent::orderedCirclesQuery($em, $entityClass);

        $q->orderBy('c.type, c.code', 'ASC');

        $circles = $q->getQuery()->execute();
        $groupedCircles = [];

        foreach ($circles as $circle) {
            $groupName = $circle->getType();
            if ($groupName === null) {
                $groupName = '';
            }
            $groupedCircles[$groupName][$circle->__toString()] = $circle->getId();
        }

        $groupedCircles = array_merge(array_flip(['Producteur', 'Fournisseur', 'Client', 'Autres']), $groupedCircles);

        return $groupedCircles;
    }
}
