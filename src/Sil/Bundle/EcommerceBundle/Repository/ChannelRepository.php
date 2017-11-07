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

namespace Sil\Bundle\EcommerceBundle\Repository;

use Sylius\Bundle\ChannelBundle\Doctrine\ORM\ChannelRepository as BaseChannelRepository;

class ChannelRepository extends BaseChannelRepository
{
    public function getAvailableAndActiveChannels()
    {
        $qb = $this->createQueryBuilder('c');

        $qb
        ->where('c.enabled = :enabled');

        $qb->setParameter('enabled', true);

        return $qb->getQuery()->getResult();
    }
}
