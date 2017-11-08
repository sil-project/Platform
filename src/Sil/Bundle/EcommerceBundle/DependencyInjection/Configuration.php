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

namespace Sil\Bundle\EcommerceBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('sil_ecommerce');

        $rootNode
            ->children()
                ->arrayNode('code_generator')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('product')->defaultValue('Sil\Bundle\EcommerceBundle\CodeGenerator\ProductCodeGenerator')->end()
                        ->scalarNode('product_variant')->defaultValue('Sil\Bundle\EcommerceBundle\CodeGenerator\ProductVariantCodeGenerator')->end()
                        ->scalarNode('product_variant_embedded')->defaultValue('Sil\Bundle\EcommerceBundle\CodeGenerator\ProductVariantCodeGenerator')->end()
                        ->scalarNode('invoice')->defaultValue('Sil\Bundle\EcommerceBundle\CodeGenerator\InvoiceCodeGenerator')->end()
                    ->end()
                ->end()
                ->arrayNode('invoice')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('template')->defaultValue('SilEcommerceBundle:Invoice:default.html.twig')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
