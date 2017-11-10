<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Sil\Bundle\StockBundle\Domain\Entity\LocationType;

/**
 * @author glenn
 */
class LocationTypeFormType extends BaseType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(array(
            'empty_data' => function (FormInterface $form) {
                return LocationType::{$form->get('value')->getData()}();
            },
            'choices'      => LocationType::getTypes(),
            'choice_value' => function ($o) {
                return null == $o ? LocationType::internal() : $o->getValue();
            },
            'choice_label' => function ($o) {
                return 'sil.stock.location_type.' . $o;
            },
        ));
    }

    public function getBlockPrefix()
    {
        return self::class;
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
