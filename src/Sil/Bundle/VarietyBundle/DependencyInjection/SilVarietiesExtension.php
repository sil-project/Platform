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

namespace Sil\Bundle\VarietyBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Blast\Bundle\CoreBundle\DependencyInjection\BlastCoreExtension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SilVarietiesExtension extends BlastCoreExtension
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

        $container->setParameter('sil_varieties', $config);

        // Entity code generators
        $container->setParameter('sil_varieties.code_generator.species',
            $container->getParameter('sil_varieties')['code_generator']['species']
        );
        $container->setParameter('sil_varieties.code_generator.variety',
            $container->getParameter('sil_varieties')['code_generator']['variety']
        );

        if ($container->getParameter('kernel.environment') == 'test') {
            $loader->load('datafixtures.yml');
        }

        $this->mergeParameter('blast', $container, __DIR__ . '/../Resources/config');
    }
}
