<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ProductBundle\Form\ProductVariant;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sil\Bundle\ProductBundle\Form\AbstractFormType;
use Sil\Bundle\ProductBundle\Form\Transformer\ProductToIdTransformer;

class ProductVariantType extends AbstractFormType
{
    /**
     * @var ProductToIdTransformer
     */
    protected $productToIdTransformer;

    protected $fieldsOrder = [
        'name',
        'code',
        'enabled',
    ];

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('id', HiddenType::class)
            ->add('product', HiddenType::class)
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
                    ]),
                ],
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
            ])
        ;

        $builder->get('product')->addModelTransformer($this->productToIdTransformer);
    }

    public function getBlockPrefix()
    {
        return 'product_variant';
    }

    /**
     * @param ProductToIdTransformer $productToIdTransformer
     */
    public function setProductToIdTransformer(ProductToIdTransformer $productToIdTransformer): void
    {
        $this->productToIdTransformer = $productToIdTransformer;
    }
}
