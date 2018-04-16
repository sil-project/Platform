<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sil\Bundle\ContactBundle\Form\DataTransformer\ArrayToCityTransformer;

/**
 * City form type.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class CityType extends AbstractType
{
    /**
     * Array to City transformer.
     *
     * @var ArrayToCityTransformer
     */
    private $transformer;

    /**
     * @param ArrayToCityTransformer $transformer
     */
    public function __construct(ArrayToCityTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'        => 'sil_contact.city.name',
                'constraints'  => [new NotBlank()],
            ])

            ->add('postCode', TextType::class, [
                'label'        => 'sil_contact.city.postcode',
                'constraints'  => [new NotBlank()],
            ])
        ;

        $builder->addModelTransformer($this->transformer);
    }
}
