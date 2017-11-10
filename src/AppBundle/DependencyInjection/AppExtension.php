<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\DependencyInjection;

use Blast\Bundle\CoreBundle\DependencyInjection\BlastCoreExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AppExtension extends BlastCoreExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        parent::load($configs, $container);
    }
}
