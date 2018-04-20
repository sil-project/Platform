<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ProductBundle\Form\OptionType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sil\Bundle\ProductBundle\Form\AbstractFormType;

class GeneralType extends AbstractFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('id', HiddenType::class)
            ->add('name', TextType::class, [
                'label'       => 'sil.product.option_type.show.group.general.fields.name',
                'required'    => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
    }

    public function getBlockPrefix()
    {
        return 'product_attribute_type_general';
    }
}
