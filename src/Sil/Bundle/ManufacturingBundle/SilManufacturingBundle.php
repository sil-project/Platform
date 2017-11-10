<?php

declare(strict_types=1);

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ManufacturingBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Sil\Bundle\ManufacturingBundle\DependencyInjection\SilManufacturingExtension;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class SilManufacturingBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new SilManufacturingExtension();
    }
}
