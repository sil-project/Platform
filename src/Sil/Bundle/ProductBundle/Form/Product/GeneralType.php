<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ProductBundle\Form\Product;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sil\Bundle\ProductBundle\Form\AbstractFormType;
use Sil\Bundle\ProductBundle\Form\Transformer\ArrayToProductTransformer;

class GeneralType extends AbstractFormType
{
    protected $fieldsOrder = [
        'name',
        'code',
        'description',
        'enabled',
    ];

    /**
     * @var ArrayToProductTransformer
     */
    protected $arrayToProductTransformer;

    public function __construct(ArrayToProductTransformer $arrayToProductTransformer)
    {
        $this->arrayToProductTransformer = $arrayToProductTransformer;
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
                'required'    => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('code', TextType::class, [
                'disabled'    => true,
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 8,
                        'max' => 8,
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'required'    => false,
                'constraints' => [
                    new Length([
                        'min' => 3,
                    ]),
                ],
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'product_general';
    }
}
