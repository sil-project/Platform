<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\ResourceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Blast\Component\Resource\Actions;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('blast_resource');
        $rootNode
          ->children()
            ->arrayNode('resources')
                ->useAttributeAsKey('name')
                ->arrayPrototype()
                  ->children()
                    ->append($this->getClassesSection())
                    ->append($this->getRoutingSection())
                    ->append($this->getApiSection())
                  ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

    private function getClassesSection(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('classes');
        $node
            ->isRequired()
            ->addDefaultsIfNotSet()
            ->children()
              ->scalarNode('model')->isRequired()->cannotBeEmpty()->end()
              ->scalarNode('repository')->cannotBeEmpty()->end()
              ->scalarNode('controller')->end()
              ->scalarNode('form')->end()
              ->scalarNode('view')->end()
              ->arrayNode('interfaces')
                ->scalarPrototype()->end()
              ->end()
            ->end();

        return $node;
    }

    private function getRoutingSection(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('routing');
        $node
            ->addDefaultsIfNotSet()
            ->children()
              ->booleanNode('enable')->defaultTrue()->end()
              ->scalarNode('prefix')->cannotBeEmpty()->end()
              ->scalarNode('base_path')->end()
              ->scalarNode('base_template')->end()
              ->scalarNode('redirect')->cannotBeEmpty()->end()
              ->arrayNode('view')
                ->children()
                  ->scalarNode('type')->end()
                  ->scalarNode('resource')->end()
                ->end()
              ->end()
              ->arrayNode('only')
                  ->scalarPrototype()->defaultNull()->end()
              ->end()
              ->arrayNode('actions')
                ->useAttributeAsKey('name')
                ->prototype('array')
                  ->children()
                    ->scalarNode('path')->end()
                    ->scalarNode('controller')->end()
                    ->arrayNode('methods')
                      ->scalarPrototype()->defaultValue([])->end()
                    ->end()
                    ->arrayNode('requirements')
                      ->useAttributeAsKey('name')
                      ->scalarPrototype()->end()
                    ->end()
                  ->end()
                ->end()
              ->end()
            ->end();

        return $node;
    }

    private function getApiSection(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('api');
        $node
          ->addDefaultsIfNotSet()
          ->children()
            ->booleanNode('enable')->defaultTrue()->end()
            ->scalarNode('prefix')->cannotBeEmpty()->end()
            ->scalarNode('base_path')->end()
            ->scalarNode('redirect')->cannotBeEmpty()->end()
            ->arrayNode('view')
              ->children()
                ->scalarNode('type')->end()
                ->scalarNode('resource')->end()
              ->end()
            ->end()
            ->arrayNode('only')
                ->scalarPrototype()->end()
            ->end();

        return $node;
    }
}
