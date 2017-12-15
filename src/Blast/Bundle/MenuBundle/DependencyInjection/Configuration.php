<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\MenuBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('blast_menu');

        $rootNode
            ->children()
                ->append(
                    $this->getMenuItemConfiguration('root')
                )
                ->append(
                    $this->getMenuItemConfiguration('settings')
                )
            ->end()
        ;

        return $treeBuilder;
    }

    protected function getMenuItemConfiguration($name, $depth = 5)
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root($name);

        if ($depth > 0) {
            $node
            ->useAttributeAsKey('id')
            ->prototype('array')
                ->children()
                    ->scalarNode('label')->end()
                    ->scalarNode('icon')->end()
                    ->scalarNode('route')->end()
                    ->scalarNode('order')->end()
                    ->booleanNode('display')->end()
                    ->arrayNode('roles')
                        ->prototype('variable')
                        ->end()
                    ->end()
                    ->scalarNode('parent')->end()
                    ->append($this->getMenuItemConfiguration('children', --$depth))
                ->end()
            ->end()
            ;
        }

        return $node;
    }
}
