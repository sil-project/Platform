<?php

declare(strict_types=1);

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Blast\Bundle\ResourceBundle\DependencyInjection\Extension\ResourceExtensionTrait;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class SilStockExtension extends Extension
{
    use ResourceExtensionTrait;

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $fileLocator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new YamlFileLoader($container, $fileLocator);
        //$loader->load('admin.yml');
        //$loader->load('services.yml');

        $newContainer = new ContainerBuilder();
        $blastLoader = new YamlFileLoader($newContainer, $fileLocator);
        $blastLoader->load('blast.yml');

        /*
         * @todo refacto stop using parameters
         */
        $container->setParameter('blast',
            array_merge($container->getParameter('blast'),
                $newContainer->getParameter('blast')));

        $this->registerResources($config['resources'], $container);
    }
}
