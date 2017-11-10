<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\SeedBatchBundle\DependencyInjection;

use Blast\Bundle\CoreBundle\DependencyInjection\BlastCoreExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @see http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class SilSeedBatchExtension extends BlastCoreExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('admin.yml');

        $container->setParameter('sil_seed_batch', $config);

        // Entity code generators
        foreach (['seed_batch', 'seed_producer', 'plot', 'seed_farm'] as $cg) {
            $container->setParameter("sil_seed_batch.code_generator.$cg",
                $container->getParameter('sil_seed_batch')['code_generator'][$cg]
            );
        }

        if ($container->getParameter('kernel.environment') == 'test') {
            $loader->load('datafixtures.yml');
        }

        $this->mergeParameter('blast', $container, __DIR__ . '/../Resources/config');

        if (class_exists('\Sil\Bundle\SecurityBundle\Configurator\SecurityConfigurator')) {
            \Sil\Bundle\SecurityBundle\Configurator\SecurityConfigurator::getInstance($container)->loadSecurityYml(__DIR__ . '/../Resources/config/security.yml');
        }
    }
}
