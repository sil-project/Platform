<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\GridBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomReportType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'gridName' => '',
            'uri'      => '',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gridName', HiddenType::class, [
                'data' => $options['gridName'],
            ])
            ->add('uri', HiddenType::class, [
                'data' => $options['uri'],
            ])
            ->add('name', TextType::class, [
                'label' => 'blast.ui.grid.custom_reports.form.name',
            ])
            ->add('public', CheckboxType::class, [
                'label'    => 'blast.ui.grid.custom_reports.form.is_public',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'blast.ui.grid.custom_reports.form.create',
            ])
        ;
    }
}
