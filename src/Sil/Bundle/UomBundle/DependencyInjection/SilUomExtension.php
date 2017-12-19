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

namespace Sil\Bundle\UomBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class SilUomExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $fileLocator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new YamlFileLoader($container, $fileLocator);

        $newContainer = new ContainerBuilder();
        $blastLoader = new YamlFileLoader($newContainer, $fileLocator);
    }
}
