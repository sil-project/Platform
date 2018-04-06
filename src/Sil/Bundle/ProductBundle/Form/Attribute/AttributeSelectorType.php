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

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sil\Bundle\ProductBundle\Form\AbstractFormType;
use Sil\Bundle\ProductBundle\Form\Transformer\AttributeTypeToIdTransformer;
use Sil\Component\Product\Model\AttributeInterface;

class AttributeSelectorType extends AbstractFormType
{
    /**
     * @var string
     */
    protected $attributeClass;

    /**
     * @var AttributeTypeToIdTransformer
     */
    protected $attributeTypeToIdTransformer;

    protected $fieldsOrder = [
        'attributeType',
        'value',
    ];

    public function __construct(string $attributeClass, AttributeTypeToIdTransformer $attributeTypeToIdTransformer)
    {
        $this->attributeClass = $attributeClass;
        $this->attributeTypeToIdTransformer = $attributeTypeToIdTransformer;
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
                ->add('attributeType', HiddenType::class, [
                    'mapped' => false,
                    'data'   => $data->getAttributeType()->getId(),
                ])
                ->add('value', ChoiceType::class, [
                    'mapped'       => false,
                    'required'     => true,
                    'choices'      => $data->getAttributeType()->getAttributes(),
                    'choice_value' => function (AttributeInterface $attribute = null) {
                        return $attribute ? $attribute->getId() : '';
                    },
                    'choice_label' => function ($value, $key, $index) {
                        return $value ? $value->getValue() : '';
                    },
                    'constraints'  => [
                        new NotBlank(),
                    ],
                    'data' => $data,
                    'attr' => [
                        'class' => 'fluid',
                    ],
                ]);
        });
    }

    public function getBlockPrefix()
    {
        return 'product_attribute_selector';
    }
}
