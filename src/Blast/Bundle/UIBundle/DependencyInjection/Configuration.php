<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UIBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('blast_ui');

        $rootNode
            ->children()
                ->scalarNode('theme')
                    ->defaultValue('default')
                ->end()
                ->arrayNode('sidebar')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('logo')
                            ->defaultValue('/bundles/blastui/img/li-logo.png')
                        ->end()
                        ->scalarNode('title')
                            ->defaultValue('BlastUI')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
