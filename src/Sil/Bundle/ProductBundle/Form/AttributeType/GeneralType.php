<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ProductBundle\Form\AttributeType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sil\Bundle\ProductBundle\Form\AbstractFormType;
use Sil\Bundle\ProductBundle\Form\Transformer\ArrayToAttributeTypeTransformer;
use Sil\Component\Product\Repository\AttributeTypeRepositoryInterface;
use Sil\Component\Product\Model\AttributeType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class GeneralType extends AbstractFormType
{
    /**
     * @var string
     */
    protected $attributeTypeClass;

    /**
     * @var AttributeTypeRepositoryInterface
     */
    protected $attributeTypeRepository;

    /**
     * @var ArrayToAttributeTypeTransformer
     */
    protected $arrayToAttributeTypeTransformer;

    protected $fieldsOrder = [
        'name',
        'type',
        'reusable',
    ];

    public function __construct(string $attributeTypeClass, AttributeTypeRepositoryInterface $attributeTypeRepository)
    {
        $this->attributeTypeClass = $attributeTypeClass;
        $this->attributeTypeRepository = $attributeTypeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('id', HiddenType::class)
            ->add('name', TextType::class, [
                'label'       => 'sil.product.product.show.group.general.fields.name',
                'required'    => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('type', ChoiceType::class, [
                'label'    => 'sil.product.product.show.group.general.fields.type',
                'disabled' => true,
                'required' => false,
                'choices'  => $this->getAttributeTypesChoices(),
            ])
            ->add('reusable', CheckboxType::class, [
                'label'    => 'sil.product.product.show.group.general.fields.reusable',
                'required' => false,
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            if ($data !== null && $data->isReusable()) {
                $form->remove('reusable')->add('reusable', CheckboxType::class, [
                    'label'    => 'sil.product.product.show.group.general.fields.reusable',
                    'required' => false,
                    'disabled' => true,
                ]);
            }
        });
    }

    protected function getAttributeTypesChoices(): array
    {
        $choices = AttributeType::getSupportedTypes();

        $translatedChoices = [];

        array_walk($choices, function (&$item, &$key) use (&$translatedChoices) {
            $newKey = sprintf('%s.%s', 'sil.product.attribute_type.values', $item);

            $translatedChoices[$newKey] = $item;
        });

        return $translatedChoices;
    }

    public function getBlockPrefix()
    {
        return 'product_attribute_type_general';
    }
}
