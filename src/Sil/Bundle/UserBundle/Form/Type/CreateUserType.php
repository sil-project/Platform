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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/* Same as Product Bundle, Should be factorized */

class CreateUserType extends BaseType
{
    /**
     * @var ArrayToUserTransformer
     */
    protected $arrayToUserTransformer;

    public function __construct(ArrayToUserTransformer $arrayToUserTransformer)
    {
        $this->arrayToUserTransformer = $arrayToUserTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
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
                'required'    => true,
                'constraints' => [
                    new NotBlank(), /* maybe it should check if it is a valid email ... (or not) */
                ],
            ])
        ;

        $builder
            ->addModelTransformer($this->arrayToUserTransformer);
    }
}
