<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\DoctrinePgsqlBundle\Model;

use Sonata\DoctrineORMAdminBundle\Model\ModelManager as BaseModelManager;
use Blast\Bundle\DoctrinePgsqlBundle\Datagrid\ProxyQuery;

class ModelManager extends BaseModelManager
{
    /**
     * {@inheritdoc}
     */
    public function createQuery($class, $alias = 'o')
    {
        $repository = $this->getEntityManager($class)->getRepository($class);

        // use Librinfo ProxyQuery instead of Sonata one
        return new ProxyQuery($repository->createQueryBuilder($alias));
    }
}
