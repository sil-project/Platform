<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\CsvImportBundle\DependencyInjection;

use Blast\Bundle\CoreBundle\DependencyInjection\BlastCoreExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\FileLoader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BlastCsvImportExtension extends BlastCoreExtension
{
    // public function doLoad(ContainerBuilder $container, FileLoader $loader, array $config)
    // {
    //      $container->setParameter('blast_utils', $config);
    // }
}
