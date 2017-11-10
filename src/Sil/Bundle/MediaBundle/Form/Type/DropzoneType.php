<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\MediaBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Blast\Bundle\CoreBundle\Form\AbstractType as BaseAbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

/**
 * File upload form type.
 */
class DropzoneType extends BaseAbstractType
{
    public function getParent()
    {
        return 'form';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('mediaContext', 'default');
        $resolver->setDefault('multipleFiles', true);
        $resolver->setDefault('dropzoneTemplate', 'SilMediaBundle:dropzone:dropzone_template.mustache.twig');

        $resolver->setAllowedTypes('mediaContext', 'string');
        $resolver->setAllowedTypes('dropzoneTemplate', 'string');
        $resolver->setAllowedTypes('multipleFiles', 'boolean');
    }

    public function getBlockPrefix()
    {
        return 'sil_dropzone';
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars = array_merge($view->vars, array(
            'mediaContext'     => $options['mediaContext'],
            'dropzoneTemplate' => $options['dropzoneTemplate'],
            'multipleFiles'    => $options['multipleFiles'],
        ));
    }
}
