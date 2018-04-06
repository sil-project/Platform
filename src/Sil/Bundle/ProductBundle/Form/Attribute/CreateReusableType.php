<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ProductBundle\Form\Attribute;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CreateReusableType extends CreateType
{
    protected $fieldsOrder = [
        'attributeType',
        'value',
    ];

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attributeType' => null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            $form
                ->remove('specificName')
                ->remove('attributeType')
                ->add('attributeType', EntityType::class, [
                    'required'     => true,
                    'class'        => $this->attributeTypeClass,
                    'choice_label' => 'name',
                    'choices'      => $this->attributeTypeRepository->findBy(['reusable' => true]),
                    'constraints'  => [
                        new NotBlank(),
                    ],
                    'data' => $data['attributeType'] !== null ? $data['attributeType'] : $options['attributeType'],
                    'attr' => [
                        'autocomplete' => 'off',
                    ],
                ]);
        });
    }

    public function getBlockPrefix()
    {
        return 'product_attribute_create_reusable';
    }
}
