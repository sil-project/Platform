<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\AccountBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sil\Bundle\AccountBundle\Form\DataTransformer\ArrayToAccountTransformer;

/**
 * Account entity form.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class AccountType extends AbstractType
{
    /**
     * Array to Account transformer.
     *
     * @var ArrayToAccountTransformer
     */
    private $transformer;

    /**
     * @param ArrayToAccountTransformer $transformer
     */
    public function __construct(ArrayToAccountTransformer $transformer)
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
                'label'        => 'sil_account.account.name',
                'constraints'  => [new NotBlank()],
            ])

            ->add('code', TextType::class, [
                'label'        => 'sil_account.account.code',
                'constraints'  => [new NotBlank()],
            ])

            ->add('save', SubmitType::class, ['label' => 'sil_account.form.save'])
        ;

        $builder->addModelTransformer($this->transformer);
    }
}
