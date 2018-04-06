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

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sil\Bundle\ProductBundle\Form\Transformer\ArrayToAttributeTransformer;
use Sil\Bundle\ProductBundle\Form\AbstractFormType;
use Sil\Component\Product\Repository\AttributeTypeRepositoryInterface;

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
     * @var ArrayToAttributeTransformer
     */
    protected $arrayToAttributeTransformer;

    protected $fieldsOrder = [
        'attributeType',
        'specificName',
        'value',
    ];

    public function __construct(string $attributeTypeClass, AttributeTypeRepositoryInterface $attributeTypeRepository, ArrayToAttributeTransformer $arrayToAttributeTransformer)
    {
        $this->attributeTypeClass = $attributeTypeClass;
        $this->attributeTypeRepository = $attributeTypeRepository;
        $this->arrayToAttributeTransformer = $arrayToAttributeTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('attributeType', EntityType::class, [
                'required'     => true,
                'class'        => $this->attributeTypeClass,
                'choice_label' => 'name',
                'choices'      => $this->attributeTypeRepository->findAll(),
                'constraints'  => [
                    new NotBlank(),
                ],
            ])
            ->add('specificName', TextType::class, [
                'required'    => false,
            ])
            ->add('value', TextType::class, [
                'required'    => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
        ;

        $builder->addModelTransformer($this->arrayToAttributeTransformer);
    }

    public function getBlockPrefix()
    {
        return 'product_attribute_create';
    }
}
