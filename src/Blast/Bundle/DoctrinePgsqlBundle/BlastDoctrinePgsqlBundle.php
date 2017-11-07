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

namespace Blast\DoctrinePgsqlBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\ORM\Query;

class BlastDoctrinePgsqlBundle extends Bundle
{
    public function boot()
    {
        parent::boot();
        $this->container
            ->get('doctrine.orm.entity_manager')
            ->getConfiguration()
            ->setDefaultQueryHint(
                Query::HINT_CUSTOM_OUTPUT_WALKER,
                'Blast\DoctrinePgsqlBundle\DoctrineExtensions\BlastWalker'
            );
    }
}
