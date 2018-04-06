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

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sil\Bundle\ProductBundle\Form\Transformer\ArrayToOptionTransformer;
use Sil\Bundle\ProductBundle\Form\AbstractFormType;
use Sil\Component\Product\Repository\OptionTypeRepositoryInterface;

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
     * @var ArrayToOptionTransformer
     */
    protected $arrayToOptionTransformer;

    protected $fieldsOrder = [
        'optionType',
        'value',
    ];

    public function __construct(string $optionTypeClass, OptionTypeRepositoryInterface $optionTypeRepository, ArrayToOptionTransformer $arrayToOptionTransformer)
    {
        $this->optionTypeClass = $optionTypeClass;
        $this->optionTypeRepository = $optionTypeRepository;
        $this->arrayToOptionTransformer = $arrayToOptionTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('optionType', EntityType::class, [
                'attr' => [
                    'autocomplete' => 'off',
                ],
                'required'     => true,
                'class'        => $this->optionTypeClass,
                'choice_label' => 'name',
                'choices'      => $this->optionTypeRepository->findAll(),
                'constraints'  => [
                    new NotBlank(),
                ],
            ])
            ->add('value', TextType::class, [
                'required'    => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
        ;

        $builder->addModelTransformer($this->arrayToOptionTransformer);
    }

    public function getBlockPrefix()
    {
        return 'product_option_create';
    }
}
