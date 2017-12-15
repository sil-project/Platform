<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Form\Type;

use Sylius\Component\Shipping\Model\ShipmentInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ShippingStateType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $choices = function (Options $options) {
            $choices = [
            'sil.shipping_state.cart'      => ShipmentInterface::STATE_CART,
            'sil.shipping_state.ready'     => ShipmentInterface::STATE_READY,
            'sil.shipping_state.shipped'   => ShipmentInterface::STATE_SHIPPED,
            'sil.shipping_state.cancelled' => ShipmentInterface::STATE_CANCELLED,
            ];
            if ($options['no_cart']) {
                unset($choices[ShipmentInterface::STATE_CART]);
            }

            return $choices;
        };

        $resolver->setDefaults(
            [
            'choices' => $choices,
            'no_cart' => false,
            ]
        );

        $resolver->setAllowedTypes('no_cart', 'bool');
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'sil_type_shipping_state';
    }
}
