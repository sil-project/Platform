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

use Sil\Bundle\ProductBundle\Form\AbstractFormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sil\Bundle\ProductBundle\Form\Transformer\ArrayToProductTransformer;
use Sil\Component\Product\Repository\OptionTypeRepositoryInterface;
use Sil\Component\Product\Model\OptionTypeInterface;

class CreateType extends AbstractFormType
{
    /**
     * @var ArrayToProductTransformer
     */
    protected $arrayToProductTransformer;

    public function __construct(ArrayToProductTransformer $arrayToProductTransformer, OptionTypeRepositoryInterface $optionTypeRepository)
    {
        $this->arrayToProductTransformer = $arrayToProductTransformer;
        $this->optionTypeRepository = $optionTypeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name', TextType::class, [
                'required'    => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('optionTypes', ChoiceType::class, [
                'required'     => false,
                'choices'      => $this->optionTypeRepository->findAll(),
                'choice_value' => function (OptionTypeInterface $optionType = null) {
                    return $optionType ? $optionType->getId() : '';
                },
                'choice_label' => function ($value, $key, $index) {
                    return $value ? $value->getName() : '';
                },
                'multiple'     => true,
                'expanded'     => true,
            ])
        ;

        $builder
            ->addModelTransformer($this->arrayToProductTransformer);
    }

    public function getBlockPrefix()
    {
        return 'product_create';
    }
}
