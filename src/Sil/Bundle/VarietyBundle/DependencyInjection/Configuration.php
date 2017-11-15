<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\VarietyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sil_variety');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
                ->arrayNode('code_generator')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('species')->defaultValue('Sil\Bundle\VarietyBundle\CodeGenerator\SpeciesCodeGenerator')->end()
                        ->scalarNode('variety')->defaultValue('Sil\Bundle\VarietyBundle\CodeGenerator\VarietyCodeGenerator')->end()
                    ->end()
                ->end()
                ->arrayNode('variety_descriptions')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->prototype('array')
                            ->children()
                                ->scalarNode('type')->defaultValue('textarea')->end()
                                ->arrayNode('options')
                                    ->children()
                                        ->booleanNode('required')->defaultFalse()->end()
                                        ->scalarNode('help')->defaultFalse()->end()
                                        ->scalarNode('label')->defaultNull()->end()
                                        ->scalarNode('choices_field')->end()
                                        ->scalarNode('template')->end()
                                        ->scalarNode('choices_class')->defaultValue('\Blast\Bundle\UtilsBundle\Entity\SelectChoice')->end()
                                        ->booleanNode('multiple')->end()
                                        ->booleanNode('expanded')->end()
                                        ->arrayNode('choices')
                                          ->prototype('scalar')->end()
                                        ->end()
                                        ->arrayNode('blast_choices')
                                          ->prototype('scalar')->end()
                                        ->end()
                                        ->arrayNode('attr')
                                            ->prototype('scalar')->end()
                                        ->end()
                                    ->end()
                                    ->addDefaultsIfNotSet()
                                ->end()
                                ->arrayNode('show')
                                    ->children()
                                        ->scalarNode('type')->defaultValue('text')->end()
                                        ->scalarNode('template')->end()
                                    ->end()
                                    ->addDefaultsIfNotSet()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
