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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sil\Bundle\ProductBundle\Form\AbstractFormType;
use Sil\Bundle\ProductBundle\Form\Transformer\ArrayToAttributeTransformer;
use Sil\Bundle\ProductBundle\Form\Transformer\AttributeTypeToIdTransformer;
use Sil\Component\Product\Repository\AttributeRepositoryInterface;
use Sil\Component\Product\Repository\AttributeTypeRepositoryInterface;
use Sil\Component\Product\Model\AttributeInterface;

class GeneralType extends AbstractFormType
{
    /**
     * @var string
     */
    protected $attributeClass;

    /**
     * @var string
     */
    protected $attributeTypeClass;

    /**
     * @var AttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     * @var AttributeTypeRepositoryInterface
     */
    protected $attributeTypeRepository;

    /**
     * @var ArrayToAttributeTransformer
     */
    protected $arrayToAttributeTransformer;

    /**
     * @var AttributeTypeToIdTransformer
     */
    protected $attributeTypeToIdTransformer;

    protected $fieldsOrder = [
        'attributeType',
        'specificName',
        'value',
    ];

    public function __construct(string $attributeTypeClass, AttributeTypeRepositoryInterface $attributeTypeRepository, ArrayToAttributeTransformer $arrayToAttributeTransformer, AttributeTypeToIdTransformer $attributeTypeToIdTransformer)
    {
        $this->attributeTypeClass = $attributeTypeClass;
        $this->attributeTypeRepository = $attributeTypeRepository;
        $this->arrayToAttributeTransformer = $arrayToAttributeTransformer;
        $this->attributeTypeToIdTransformer = $attributeTypeToIdTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('id', HiddenType::class)
            ->add('attributeType', HiddenType::class)
            ->add('specificName', TextType::class, [
                'required'    => false,
                'attr'        => [
                    'placeholder' => 'sil.product.attribute.show.group.general.fields.specificName',
                ],
            ])
            ->add('value', TextType::class, [
                'required'    => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        $builder->get('attributeType')->addModelTransformer($this->attributeTypeToIdTransformer);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            $isAttributeReusable = false;

            if ($data instanceof AttributeInterface) {
                $isAttributeReusable = $data->getAttributeType()->isReusable();
                $attributes = $data->getAttributeType()->getAttributes();
            } else {
                $isAttributeReusable = $data['attributeType']->isReusable();
                $attributes = $data['attributeType']->getAttributes();
            }

            if ($isAttributeReusable) {
                $form
                    ->remove('specificName')
                    ->add('specificName', HiddenType::class, []);
            }
        });
    }

    public function getBlockPrefix()
    {
        return 'product_attribute_general';
    }
}
