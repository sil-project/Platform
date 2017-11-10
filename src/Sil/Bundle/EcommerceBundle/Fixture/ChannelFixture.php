<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Fixture;

use Sylius\Bundle\CoreBundle\Fixture\ChannelFixture as BaseFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ChannelFixture extends BaseFixture
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'li_channel';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        //        parent::configureResourceNode($resourceNode);
        //        $resourceNode
        //            ->children()
        //                ->scalarNode('account_verified_required')->end()
        //        ;

        $resourceNode
            ->children()
            ->scalarNode('name')->cannotBeEmpty()->end()
            ->scalarNode('code')->cannotBeEmpty()->end()
            ->scalarNode('hostname')->cannotBeEmpty()->end()
            ->scalarNode('color')->cannotBeEmpty()->end()
            ->scalarNode('default_tax_zone')->end()
            ->scalarNode('tax_calculation_strategy')->end()
            ->booleanNode('enabled')->end()
            ->booleanNode('skipping_shipping_step_allowed')->end()
            ->booleanNode('skipping_payment_step_allowed')->end()
            ->scalarNode('default_locale')->cannotBeEmpty()->end()
            ->arrayNode('locales')->prototype('scalar')->end()->end()
            ->scalarNode('base_currency')->cannotBeEmpty()->end()
            ->arrayNode('currencies')->prototype('scalar')->end()->end()
            ->scalarNode('theme_name')->end()
            ->scalarNode('contact_email')->end()
            ->booleanNode('account_verified_required')->end();
    }
}
