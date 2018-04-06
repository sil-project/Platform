<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\UserBundle\Form\Type;

use Sil\Bundle\UserBundle\Form\Transformer\ArrayToUserTransformer;
use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/* Same as Product Bundle, Should be factorized */

class EditUserType extends BaseType
{
    /**
     * @var ArrayToUserTransformer
     */
    protected $arrayToUserTransformer;

    protected $fieldsOrder = [
        'username',
        'password',
        'email',
        'enabled',
    ];

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        parent::finishView($view, $form, $options);
        $view->children = array_replace(array_flip($this->fieldsOrder), $view->children);
    }

    public function __construct(ArrayToUserTransformer $arrayToUserTransformer)
    {
        $this->arrayToUserTransformer = $arrayToUserTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('id', HiddenType::class)
            ->add('username', TextType::class, [
                'required'    => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('password', TextType::class, [
                'required'    => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('email', TextType::class, [
                'required'     => true,
                 'constraints' => [
                    new NotBlank(),
                 ],
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'user_edit';
    }
}
