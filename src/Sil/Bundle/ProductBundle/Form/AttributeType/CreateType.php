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

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sil\Bundle\ProductBundle\Form\Transformer\ArrayToAttributeTypeTransformer;
use Sil\Component\Product\Repository\AttributeTypeRepositoryInterface;
use Sil\Component\Product\Model\AttributeType;
use Sil\Bundle\ProductBundle\Form\AbstractFormType;

class CreateType extends AbstractFormType
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

    public function __construct(string $attributeTypeClass, AttributeTypeRepositoryInterface $attributeTypeRepository, ArrayToAttributeTypeTransformer $arrayToAttributeTypeTransformer)
    {
        $this->attributeTypeClass = $attributeTypeClass;
        $this->attributeTypeRepository = $attributeTypeRepository;
        $this->arrayToAttributeTypeTransformer = $arrayToAttributeTypeTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name', TextType::class, [
                'label'       => 'sil.product.attribute_type.create.form.fields.name',
                'required'    => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('type', ChoiceType::class, [
                'label'        => 'sil.product.attribute_type.create.form.fields.type',
                'required'     => true,
                'choices'      => $this->getAttributeTypesChoices(),
                'constraints'  => [
                    new NotBlank(),
                ],
            ])
            ->add('reusable', CheckboxType::class, [
                'label'       => 'sil.product.attribute_type.create.form.fields.reusable',
                'required'    => false,
            ])
        ;

        $builder
            ->addModelTransformer($this->arrayToAttributeTypeTransformer);
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
        return 'product_attribute_type_create';
    }
}
