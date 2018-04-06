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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sil\Bundle\ProductBundle\Form\Transformer\ArrayToAttributeTransformer;
use Sil\Bundle\ProductBundle\Form\AbstractFormType;
use Sil\Component\Product\Repository\AttributeTypeRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class ChooseType extends AbstractFormType
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
     * @var AttributeTypeRepositoryInterface
     */
    protected $attributeTypeRepository;

    /**
     * @var ArrayToAttributeTransformer
     */
    protected $arrayToAttributeTransformer;

    public function __construct(string $attributeClass, string $attributeTypeClass, AttributeTypeRepositoryInterface $attributeTypeRepository, ArrayToAttributeTransformer $arrayToAttributeTransformer)
    {
        $this->attributeClass = $attributeClass;
        $this->attributeTypeClass = $attributeTypeClass;
        $this->attributeTypeRepository = $attributeTypeRepository;
        $this->arrayToAttributeTransformer = $arrayToAttributeTransformer;
    }

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

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            $form = $event->getForm();
            $data = $event->getData();
            $attributeTypes = $this->attributeTypeRepository->findAll();

            $attributeType = $data['attributeType'] !== null ? $data['attributeType'] : $options['attributeType'];

            if ($attributeType === null && $data['attributeType'] === null) {
                $attributeType = isset($attributeTypes[0]) ? $attributeTypes[0] : null;
            }

            $form
                ->add('attributeType', EntityType::class, [
                    'required'     => true,
                    'class'        => $this->attributeTypeClass,
                    'choice_label' => 'name',
                    'choices'      => $attributeTypes,
                    'constraints'  => [
                        new NotBlank(),
                    ],
                    'data' => $attributeType,
                ]);

            if ($attributeType !== null && $attributeType->isReusable()) {
                $form
                    ->add('value', EntityType::class, [
                        'mapped'       => false,
                        'class'        => $this->attributeClass,
                        'required'     => true,
                        'choice_label' => function ($attribute) {
                            return $attribute->getValue();
                        },
                        'query_builder' => function (EntityRepository $er) use ($attributeType) {
                            $qb = $er->createQueryBuilder('a');

                            $qb
                                ->where('a.attributeType = :attributeType')
                                ->setParameter('attributeType', $attributeType);

                            return $qb;
                        },
                        'constraints' => [
                            new NotBlank(),
                        ],
                    ])
                    ->add('specificName', HiddenType::class, []);
            } else {
                $form
                    ->add('specificName', TextType::class, [
                        'required'    => false,
                    ])
                    ->add('value', TextType::class, [
                        'required'    => true,
                        'constraints' => [
                            new NotBlank(),
                        ],
                    ]);
            }
        });
    }

    public function getBlockPrefix()
    {
        return 'product_attribute_choose';
    }
}
