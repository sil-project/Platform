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
use Sil\Bundle\StockBundle\Domain\Entity\LocationType;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class LocationFilter
{
    public function filterByInternal(LocationRepositoryInterface $repo)
    {
        return $repo->createQueryBuilder('l')
                ->where('l.typeValue = :type')
                ->setParameter('type', LocationType::INTERNAL);
    }
}
