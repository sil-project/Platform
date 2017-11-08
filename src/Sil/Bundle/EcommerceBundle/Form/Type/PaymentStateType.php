<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Form\Type;

use Sylius\Component\Core\OrderPaymentStates;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class PaymentStateType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $choices = function (Options $options) {
            $choices = [
            'sil.payment_state.new'                => 'new',
            'sil.payment_state.cart'               => OrderPaymentStates::STATE_CART,
            'sil.payment_state.awaiting_payment'   => OrderPaymentStates::STATE_AWAITING_PAYMENT,
            'sil.payment_state.partially_paid'     => OrderPaymentStates::STATE_PARTIALLY_PAID,
            'sil.payment_state.cancelled'          => OrderPaymentStates::STATE_CANCELLED,
            'sil.payment_state.paid'               => OrderPaymentStates::STATE_PAID,
            'sil.payment_state.partially_refunded' => OrderPaymentStates::STATE_PARTIALLY_REFUNDED,
            'sil.payment_state.refunded'           => OrderPaymentStates::STATE_REFUNDED,
            ];
            if ($options['no_cart']) {
                unset($choices['sil.payment_state.cart']);
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
        return 'sil_type_payment_state';
    }
}
