<?php

declare(strict_types=1);

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Admin;

use Blast\Bundle\ResourceBundle\Sonata\Admin\ResourceAdmin;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class OperationTypeAdmin extends ResourceAdmin
{
    protected $baseRouteName = 'admin_stock_operationtype';
    protected $baseRoutePattern = 'stock/operationtype';

    public function toString($opType)
    {
        return $opType->getName();
    }
}
