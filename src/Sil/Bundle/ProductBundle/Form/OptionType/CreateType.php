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

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sil\Bundle\ProductBundle\Form\Transformer\ArrayToOptionTypeTransformer;
use Sil\Component\Product\Repository\OptionTypeRepositoryInterface;
use Sil\Bundle\ProductBundle\Form\AbstractFormType;

class CreateType extends AbstractFormType
{
    /**
     * @var string
     */
    protected $optionTypeClass;

    /**
     * @var OptionTypeRepositoryInterface
     */
    protected $optionTypeRepository;

    /**
     * @var ArrayToOptionTypeTransformer
     */
    protected $arrayToOptionTypeTransformer;

    protected $fieldsOrder = [
        'name',
    ];

    public function __construct(string $optionTypeClass, OptionTypeRepositoryInterface $optionTypeRepository, ArrayToOptionTypeTransformer $arrayToOptionTypeTransformer)
    {
        $this->optionTypeClass = $optionTypeClass;
        $this->optionTypeRepository = $optionTypeRepository;
        $this->arrayToOptionTypeTransformer = $arrayToOptionTypeTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name', TextType::class, [
                'label'       => 'sil.product.option_type.create.form.fields.name',
                'required'    => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
        ;

        $builder
            ->addModelTransformer($this->arrayToOptionTypeTransformer);
    }

    public function getBlockPrefix()
    {
        return 'product_option_type_create';
    }
}
