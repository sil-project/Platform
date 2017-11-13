<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UtilsBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Blast\Bundle\CoreBundle\Form\AbstractType as BaseAbstractType;

class UniqueFieldType extends BaseAbstractType
{
    public function getParent()
    {
        return 'text';
    }

    public function getBlockPrefix()
    {
        return 'blast_unique_field';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'return_link' => 'false',
        ]);

        $resolver->setDefined('return');
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['return_link'] = $options['return_link'];
    }
}
