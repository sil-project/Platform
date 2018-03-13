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
                ->arrayNode('themes')
                    ->scalarPrototype()->end()
                    ->defaultValue(['default', 'dark'])
                ->end()
                ->scalarNode('defaultTheme')
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
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('form_type_button')
                            ->defaultValue('@BlastUI/Form/Type/button.html.twig')
                        ->end()
                        ->scalarNode('form_type_checkbox')
                            ->defaultValue('@BlastUI/Form/Type/checkbox.html.twig')
                        ->end()
                        ->scalarNode('form_type_checkboxes')
                            ->defaultValue('@BlastUI/Form/Type/checkboxes.html.twig')
                        ->end()
                        ->scalarNode('form_type_datetime')
                            ->defaultValue('@BlastUI/Form/Type/datetime.html.twig')
                        ->end()
                        ->scalarNode('form_type_hidden')
                            ->defaultValue('@BlastUI/Form/Type/hidden.html.twig')
                        ->end()
                        ->scalarNode('form_type_link')
                            ->defaultValue('@BlastUI/Form/Type/link.html.twig')
                        ->end()
                        ->scalarNode('form_type_number')
                            ->defaultValue('@BlastUI/Form/Type/number.html.twig')
                        ->end()
                        ->scalarNode('form_type_select')
                            ->defaultValue('@BlastUI/Form/Type/select.html.twig')
                        ->end()
                        ->scalarNode('form_type_submit')
                            ->defaultValue('@BlastUI/Form/Type/submit.html.twig')
                        ->end()
                        ->scalarNode('form_type_text')
                            ->defaultValue('@BlastUI/Form/Type/text.html.twig')
                        ->end()
                        ->scalarNode('form_type_textarea')
                            ->defaultValue('@BlastUI/Form/Type/textarea.html.twig')
                        ->end()
                        ->scalarNode('widget_datacard_card')
                            ->defaultValue('@BlastUI/Widget/DataCard/card.html.twig')
                        ->end()
                        ->scalarNode('widget_field_form_group')
                            ->defaultValue('@BlastUI/Widget/Field/form_group.html.twig')
                        ->end()
                        ->scalarNode('widget_field_show_group')
                            ->defaultValue('@BlastUI/Widget/Field/show_group.html.twig')
                        ->end()
                        ->scalarNode('widget_panel')
                            ->defaultValue('@BlastUI/Widget/Panel/panel.html.twig')
                        ->end()
                        ->scalarNode('widget_simple_panel')
                            ->defaultValue('@BlastUI/Widget/Panel/simple_panel.html.twig')
                        ->end()
                        ->scalarNode('widget_step_nav')
                            ->defaultValue('@BlastUI/Widget/Step/nav.html.twig')
                        ->end()
                        ->scalarNode('widget_step_header')
                            ->defaultValue('@BlastUI/Widget/Step/header.html.twig')
                        ->end()
                        ->scalarNode('widget_table')
                            ->defaultValue('@BlastUI/Widget/Table/table.html.twig')
                        ->end()
                        ->scalarNode('widget_modal')
                            ->defaultValue('@BlastUI/Widget/Modal/modal.html.twig')
                        ->end()
                    ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
