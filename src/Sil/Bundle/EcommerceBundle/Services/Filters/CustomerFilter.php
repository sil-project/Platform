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

namespace Librinfo\EcommerceBundle\Services\Filters;

use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;

class CustomerFilter
{
    public function filterOnFullname($queryBuilder, $alias, $field, $value)
    {
        if (!isset($value['value'])) {
            return;
        }

        if (!isset($value['type'])) {
            $value['type'] = ChoiceType::TYPE_CONTAINS;
        }

        switch ($value['type']) {
        case ChoiceType::TYPE_CONTAINS:
        default:
                $queryBuilder->andWhere(
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->like('customer.firstName', ':value'),
                        $queryBuilder->expr()->like('customer.lastName', ':value')
                    )
                );
            break;
        case ChoiceType::TYPE_NOT_CONTAINS:
                $queryBuilder->andWhere(
                    $queryBuilder->expr()->not(
                        $queryBuilder->expr()->orX(
                            $queryBuilder->expr()->like('customer.firstName', ':value'),
                            $queryBuilder->expr()->like('customer.lastName', ':value')
                        )
                    )
                );
            break;
        }

        $queryBuilder->setParameter('value', '%' . $value['value'] . '%');

        return true;
    }
}
