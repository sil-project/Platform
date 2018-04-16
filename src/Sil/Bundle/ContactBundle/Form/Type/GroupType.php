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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sil\Bundle\ContactBundle\Form\DataTransformer\ArrayToGroupTransformer;

/**
 * Group entity form.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class GroupType extends AbstractType
{
    /**
     * Array to Group transformer.
     *
     * @var ArrayToGroupTransformer
     */
    private $transformer;

    /**
     * Group model FQCN.
     *
     * @var string
     */
    private $modelClass;

    /**
     * @param ArrayToGroupTransformer $transformer
     */
    public function __construct(ArrayToGroupTransformer $transformer, string $modelClass)
    {
        $this->transformer = $transformer;
        $this->modelClass = $modelClass;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'       => 'sil_contact.group.name',
                'constraints' => [new NotBlank()],
            ])

            ->add('parent', NestedTreeableType::class, [
                'label'       => 'sil_contact.group.parent',
                'class'       => $this->modelClass,
                'placeholder' => 'sil_contact.form.placeholder.select',
                'required'    => false,
            ])
            ->add('save', SubmitType::class, ['label' => 'sil_contact.form.save'])
        ;

        $builder->addModelTransformer($this->transformer);
    }
}
