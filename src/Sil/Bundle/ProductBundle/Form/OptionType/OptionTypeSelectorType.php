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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sil\Bundle\ProductBundle\Form\AbstractFormType;
use Sil\Component\Product\Repository\OptionTypeRepositoryInterface;
use Sil\Component\Product\Model\OptionTypeInterface;

class OptionTypeSelectorType extends AbstractFormType
{
    /**
     * @var OptionTypeRepositoryInterface
     */
    protected $optionTypeRepository;

    public function __construct(OptionTypeRepositoryInterface $optionTypeRepository)
    {
        $this->optionTypeRepository = $optionTypeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('optionTypes', ChoiceType::class, [
                'label'        => 'sil.product.product.show.group.optionTypes.title',
                'required'     => true,
                'choices'      => $this->optionTypeRepository->findAll(),
                'choice_value' => function (OptionTypeInterface $optionType = null) {
                    return $optionType ? $optionType->getId() : '';
                },
                'choice_label' => function ($value, $key, $index) {
                    return $value ? $value->getName() : '';
                },
                'constraints'  => [
                    new NotBlank(),
                ],
                'multiple'     => true,
                'expanded'     => true,
            ]);
    }

    public function getBlockPrefix()
    {
        return 'product_attribute_type_option_type_selector';
    }
}
