<?php

namespace Blast\Bundle\MenuBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('blast_menu');

        // @TODO: Handle menu configuration by bundle configuration insteadof container parameter
        // 
        // $rootNode
        //     ->children()
        //         ->arrayNode('root')
        //             ->prototype('array')
        //                 ->children()
        //                     ->scalarNode('class')->end()
        //                     ->variableNode('fields')->end()
        //                 ->end()
        //             ->end()
        //         ->arrayNode('settings')

        return $treeBuilder;
    }
}
