<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use  Sil\Bundle\StockBundle\DependencyInjection\SilStockExtension;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class SilStockBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new SilStockExtension();
    }
}
