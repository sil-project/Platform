<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;
use Sylius\Bundle\AddressingBundle\Form\Type\CountryCodeChoiceType;

class OrderAddressType extends BaseType
{
    /**
     * @var string
     */
    private $addressClass;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label'       => 'sil.ecommerce.order_address.form.label.email',
                'required'    => true,
                'mapped'      => false,
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('firstName', TextType::class, [
                'label'    => 'sil.ecommerce.order_address.form.label.first_name',
                'required' => true,
                'attr'     => [
                    'class'    => 'inline-block',
                    'width'    => 50,
                ],
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('lastName', TextType::class, [
                'label'    => 'sil.ecommerce.order_address.form.label.last_name',
                'required' => true,
                'attr'     => [
                    'class'    => 'new-line-after',
                    'width'    => 50,
                ],
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('company', TextType::class, [
                'required' => false,
                'label'    => 'sil.ecommerce.order_address.form.label.company',
                'mapped'   => false,
            ])
            ->add('street', TextType::class, [
                'required'    => true,
                'label'       => 'sil.ecommerce.order_address.form.label.street',
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('countryCode', CountryCodeChoiceType::class, [
                'required'    => true,
                'label'       => 'sil.ecommerce.order_address.form.label.country_code',
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('provinceName', TextType::class, [
                'required' => false,
                'label'    => 'sil.ecommerce.order_address.form.label.province_name',
            ])
            ->add('city', TextType::class, [
                'required' => true,
                'label'    => 'sil.ecommerce.order_address.form.label.city',
                'attr'     => [
                    'class' => 'inline-block',
                    'width' => 50,
                ],
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('postCode', TextType::class, [
                'required' => true,
                'label'    => 'sil.ecommerce.order_address.form.label.post_code',
                'attr'     => [
                    'class' => 'inline-block',
                    'width' => 50,
                ],
                'constraints' => [
                    new NotNull(),
                ],
            ])
       ;

        if ($builder->getName() === 'shippingAddress') {
            $builder
                ->add('useSameAddressForBilling', CheckboxType::class, [
                    'required' => false,
                    'label'    => 'sil.ecommerce.order_address.form.label.useSameAddressForBilling',
                    'mapped'   => false,
                ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => $this->addressClass,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return self::class;
    }

    /**
     * @param string addressClass
     */
    public function setAddressClass(string $addressClass): void
    {
        $this->addressClass = $addressClass;
    }
}
