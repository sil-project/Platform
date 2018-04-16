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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Sil\Bundle\ContactBundle\Entity\Contact;

/**
 * Contact entity form.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label'        => 'sil_contact.contact.firstname',
                'constraints'  => [new NotBlank()],
            ])

            ->add('lastName', TextType::class, [
                'label'        => 'sil_contact.contact.lastname',
                'constraints'  => [new NotBlank()],
            ])

            ->add('title', ChoiceType::class, [
                'label'   => 'sil_contact.contact.title',
                'choices' => Contact::getTitleChoices(),
            ])
            ->add('email', EmailType::class, [
                'label'       => 'sil_contact.contact.email',
                'constraints' => [new Email()],
            ])

            ->add('position', TextType::class, [
                'label'    => 'sil_contact.contact.position',
                'required' => false,
            ])

            ->add('save', SubmitType::class, ['label' => 'sil_contact.form.save'])
        ;
    }
}
