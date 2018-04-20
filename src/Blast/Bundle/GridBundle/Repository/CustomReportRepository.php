<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\GridBundle\Repository;

use Blast\Bundle\ResourceBundle\Doctrine\ORM\Repository\ResourceRepository;
use Blast\Component\Grid\Model\CustomReportOwnerInterface;
use Blast\Component\Grid\Repository\CustomReportRepositoryInterface;

class CustomReportRepository extends ResourceRepository implements CustomReportRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getCustomReportsForGridNameAndOwner(string $gridName, CustomReportOwnerInterface $owner): array
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $or = $queryBuilder->expr()->orX();

        $or->add(
            $queryBuilder->expr()->andX(
                $queryBuilder->expr()->eq('o.gridName', ':gridName'),
                $queryBuilder->expr()->eq('o.owner', ':ownerId')
            )
        );

        $or->add(
            $queryBuilder->expr()->andX(
                $queryBuilder->expr()->eq('o.gridName', ':gridName'),
                $queryBuilder->expr()->eq('o.public', ':public')
            )
        );

        $queryBuilder->where($or);

        $queryBuilder->setParameters([
            'gridName' => $gridName,
            'ownerId'  => $owner->getId(),
            'public'   => true,
        ]);

        $result = $queryBuilder->getQuery()->getResult();

        return count($result) > 0 ? $result : [];
    }
}
