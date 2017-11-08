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

namespace Blast\Bundle\BaseEntitiesBundle\Admin\Traits;

trait NestedTreeableAdmin
{
    public function createQuery($context = 'list')
    {
        $proxyQuery = parent::createQuery($context);
        // Default Alias is "o"
        $proxyQuery->addOrderBy('o.treeRoot', 'ASC');
        $proxyQuery->addOrderBy('o.treeLft', 'ASC');

        return $proxyQuery;
    }
}
