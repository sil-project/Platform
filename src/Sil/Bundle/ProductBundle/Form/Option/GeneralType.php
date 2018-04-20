<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ProductBundle\Form\Option;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sil\Bundle\ProductBundle\Form\AbstractFormType;
use Sil\Bundle\ProductBundle\Form\Transformer\OptionTypeToIdTransformer;

class GeneralType extends AbstractFormType
{
    /**
     * @var OptionTypeToIdTransformer
     */
    protected $optionTypeToIdTransformer;

    protected $fieldsOrder = [
        'optionType',
        'value',
    ];

    public function __construct(OptionTypeToIdTransformer $optionTypeToIdTransformer)
    {
        $this->optionTypeToIdTransformer = $optionTypeToIdTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('id', HiddenType::class)
            ->add('optionType', HiddenType::class)
            ->add('value', TextType::class, [
                'label'       => 'sil.product.option.show.group.general.fields.value',
                'required'    => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        $builder->get('optionType')->addModelTransformer($this->optionTypeToIdTransformer);
    }

    public function getBlockPrefix()
    {
        return 'product_option_general';
    }
}
