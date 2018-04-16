<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sil\Bundle\ContactBundle\Form\DataTransformer\NameToCountryTransformer;
use Sil\Bundle\ContactBundle\Form\DataTransformer\ArrayToAddressTransformer;
use Sil\Bundle\ContactBundle\Form\DataTransformer\NameToProvinceTransformer;
use Sil\Bundle\ContactBundle\Form\DataTransformer\IdToContactTransformer;
use Sil\Bundle\ContactBundle\Entity\Address;

/**
 * Address entity form.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class AddressType extends AbstractType
{
    /**
     * Array to Address transformer.
     *
     * @var ArrayToAddressTransformer
     */
    protected $transformer;

    /**
     * Id to Contact transformer.
     *
     * @var IdToContactTransformer
     */
    protected $contactTransformer;

    /**
     * Id to Contact transformer.
     *
     * @var IdToContactTransformer
     */
    protected $countryTransformer;

    /**
     * Id to Contact transformer.
     *
     * @var IdToContactTransformer
     */
    protected $provinceTransformer;

    /**
     * Country class name.
     *
     * @var string
     */
    protected $countryClass;

    /**
     * Province class name.
     *
     * @var string
     */
    protected $provinceClass;

    /**
     * @param ArrayToAddressTransformer $transformer
     */
    public function __construct(
        ArrayToAddressTransformer $transformer,
        IdToContactTransformer $contactTransformer,
        NameToCountryTransformer $countryTransformer,
        NameToProvinceTransformer $provinceTransformer,
        string $countryClass,
        string $provinceClass
    ) {
        $this->transformer = $transformer;
        $this->contactTransformer = $contactTransformer;
        $this->provinceTransformer = $provinceTransformer;
        $this->countryTransformer = $countryTransformer;
        $this->countryClass = $countryClass;
        $this->provinceClass = $provinceClass;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('street', TextType::class, [
                'label'        => 'sil_contact.address.street',
                'constraints'  => [new NotBlank()],
                'required'     => true,
            ])

            ->add('city', CityType::class, [
                'label'        => 'sil_contact.address.city',
                'constraints'  => [new NotBlank()],
            ])

            ->add('province', EntityType::class, [
                'label'        => 'sil_contact.address.province',
                'class'        => $this->provinceClass,
                'placeholder'  => '',
                'choice_label' => 'name',
                'required'     => false,
            ])

            ->add('country', EntityType::class, [
                'label'        => 'sil_contact.address.country',
                'class'        => $this->countryClass,
                'choice_label' => 'name',
                'required'     => true,
            ])

            ->add('type', ChoiceType::class, [
                'label'        => 'sil_contact.address.type',
                'choices'      => Address::getTypes(),
                'required'     => false,
            ])

            ->add('contact', HiddenType::class, ['required' => false])

            ->add('save', SubmitType::class, ['label' => 'sil_contact.form.save'])
        ;

        $builder->addModelTransformer($this->transformer);
        $builder->get('contact')->addModelTransformer($this->contactTransformer);
        $builder->get('province')->addModelTransformer($this->provinceTransformer);
        $builder->get('country')->addModelTransformer($this->countryTransformer);
    }
}
