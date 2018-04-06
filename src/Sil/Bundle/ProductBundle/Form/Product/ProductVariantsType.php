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

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Sil\Bundle\ProductBundle\Form\AbstractFormType;
use Sil\Bundle\ProductBundle\Form\ProductVariant\ProductVariantType;

class ProductVariantsType extends AbstractFormType
{
    /**
     * @var string
     */
    protected $productVariantClass;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('variants', CollectionType::class, [
                'entry_type'    => ProductVariantType::class,
                'entry_options' => [
                    'compound'   => true,
                    'data_class' => $this->productVariantClass,
                ],
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'product_variants_collection';
    }

    /**
     * @param string $productVariantClass
     */
    public function setProductVariantClass(string $productVariantClass): void
    {
        $this->productVariantClass = $productVariantClass;
    }
}
