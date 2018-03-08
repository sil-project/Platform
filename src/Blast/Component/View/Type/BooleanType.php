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
use Blast\Component\View\DataTransformerInterface;
use Blast\Component\View\ViewBuilderInterface;

/**
  * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
  */
 class BooleanType extends BaseType implements DataTransformerInterface
 {
     public function buildView(ViewBuilderInterface $builder, array $options)
     {
         $builder->addDataTransformer($this);
     }

     public function configureOptions(OptionsResolver $resolver)
     {
         $resolver->setDefaults(array(
         'compound' => false,
     ));
     }

     public function getBlockPrefix(): string
     {
         return 'view_boolean';
     }

     /**
      * {@inheritdoc}
      */
     public function transform($data)
     {
         return (bool) $data;
     }

     /**
      * {@inheritdoc}
      */
     public function reverseTransform($data)
     {
         return null === $data ? false : (bool) $data;
     }
 }
