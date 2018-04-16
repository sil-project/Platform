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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber;
use Sil\Bundle\ContactBundle\Form\DataTransformer\ArrayToPhoneTransformer;
use Sil\Bundle\ContactBundle\Form\DataTransformer\IdToContactTransformer;
use Sil\Bundle\ContactBundle\Entity\Phone;

/**
 * Phone entity form.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class PhoneType extends AbstractType
{
    /**
     * Array to Phone transformer.
     *
     * @var ArrayToPhoneTransformer
     */
    protected $transformer;

    /**
     * Id to Contact transformer.
     *
     * @var IdToContactTransformer
     */
    protected $contactTransformer;

    /**
     * Current app locale.
     *
     * @var string
     */
    protected $locale;

    /**
     * @param ArrayToPhoneTransformer $transformer
     * @param IdToContactTransformer  $contactTransformer
     * @param string                  $locale
     */
    public function __construct(ArrayToPhoneTransformer $transformer, IdToContactTransformer $contactTransformer, string $locale)
    {
        $this->transformer = $transformer;
        $this->contactTransformer = $contactTransformer;
        $this->locale = strtoupper($locale);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', TelType::class, [
                'label'       => 'sil_contact.phone.number',
                'constraints' => [
                    new NotBlank(),
                    new PhoneNumber(['defaultRegion' => $this->locale]),
                ],
            ])

            ->add('type', ChoiceType::class, [
                'label'    => 'sil_contact.phone.type',
                'choices'  => Phone::getTypes(),
                'required' => false,
            ])

            ->add('contact', HiddenType::class, ['required' => false])

            ->add('save', SubmitType::class, ['label' => 'sil_contact.form.save'])
        ;

        $builder->addModelTransformer($this->transformer);
        $builder->get('contact')->addModelTransformer($this->contactTransformer);
    }
}
