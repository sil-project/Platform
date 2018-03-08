<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\View\Type;

use Blast\Component\View\Builder\ViewBuilderInterface;
use Blast\Component\View\ViewInterface;
use Blast\Component\View\RenderingView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
  * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
  */
 class CollectionType extends AbstractType
 {
     public function buildView(ViewBuilderInterface $builder, array $options)
     {
         parent::buildView($builder, $options);

         if ($options['prototype']) {
             $prototypeOptions = array_replace(array(
                'label'    => $options['prototype_name'] . 'label__',
            ), $options['entry_options']);

             if (null !== $options['prototype_data']) {
                 $prototypeOptions['data'] = $options['prototype_data'];
             }

             $prototype = $builder->create($options['prototype_name'], $options['entry_type'], $prototypeOptions);
             $builder->setAttribute('prototype', $prototype->getView());
         }
     }

     /**
      * {@inheritdoc}
      */
     public function buildRenderingView(RenderingView $rview, ViewInterface $view, array $options)
     {
         parent::buildRenderingView($rview, $view, $options);

         $rview->vars = array_replace($rview->vars, array(
            'allow_add'    => $options['allow_add'],
            'allow_delete' => $options['allow_delete'],
        ));

         if ($view->getConfig()->hasAttribute('prototype')) {
             $prototype = $view->getConfig()->getAttribute('prototype');
             $rview->vars['prototype'] = $prototype->setParent($view)->createRenderingView($rview);

             $prototype->setParent($view);

             foreach ($view->getData() as $index => $data) {
                 $prototype->getConfig()->setName($index);
                 $prototype->setData($data);
                 $rview->children[] = $prototype->createRenderingView($rview);
             }
         }
     }

     /**
      * {@inheritdoc}
      */
     public function finishRenderingView(RenderingView $rview, ViewInterface $view, array $options)
     {
         parent::finishRenderingView($rview, $view, $options);
     }

     /**
      * {@inheritdoc}
      */
     public function configureOptions(OptionsResolver $resolver)
     {
         parent::configureOptions($resolver);

         $entryOptionsNormalizer = function (Options $options, $value) {
             $value['block_name'] = 'entry';

             return $value;
         };

         $resolver->setDefaults(array(
            'allow_add'      => false,
            'allow_delete'   => false,
            'prototype'      => true,
            'prototype_data' => null,
            'prototype_name' => '__name__',
            'entry_type'     => __NAMESPACE__ . '\TextType',
            'entry_options'  => array(),
            'delete_empty'   => false,
        ));

         $resolver->setNormalizer('entry_options', $entryOptionsNormalizer);
     }

     public function getBlockPrefix(): string
     {
         return 'view_collection';
     }
 }
