<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\View\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
  * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
  */
 class TextType extends BaseType
 {
     public function configureOptions(OptionsResolver $resolver)
     {
         $resolver->setDefaults(array(
         'compound' => false,
     ));
     }

     public function getBlockPrefix(): string
     {
         return 'view_text';
     }
 }
