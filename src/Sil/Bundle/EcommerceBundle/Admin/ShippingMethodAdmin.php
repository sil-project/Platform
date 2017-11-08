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

namespace Sil\Bundle\EcommerceBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sil\Bundle\EcommerceBundle\Form\Type\PriceCentsType;

class ShippingMethodAdmin extends SyliusGenericAdmin
{
    public function genChannelArray(string $sonataType = 'sonata_type_immutable_array')
    {
        $channelKeyTab = [];

        foreach ($this->getConfigurationPool()->getContainer()
        ->get('sylius.repository.channel')->findAll() as $channel) {
            $channelKeyTab[] = [$channel->getCode(), $sonataType, [
                'keys' => [
                    ['amount', PriceCentsType::class, ['label'    => false]],
                ],
            ]];
        }

        return $channelKeyTab;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        /* @todo: we should never use explicit tab and group name in php code as it may be changed in blast.yml */
        $formMapper
        ->tab('form_tab_general')->with('form_group_parameters')
        ->add(
            'configuration',
            'sonata_type_immutable_array',
            ['label'    => 'sil.ecommercebundle.amount',
            'required'  => false,
            'keys'      => $this->genChannelArray('sonata_type_immutable_array'), ]
        )
        ->end()->end();
    }
}
