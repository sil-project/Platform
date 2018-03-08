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

use Blast\Component\View\ViewBuilderInterface;
use Blast\Component\View\ViewInterface;
use Blast\Component\View\RenderingView;
use Blast\Component\View\DataMapper\PropertyPathMapper;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
  * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
  */
 class ViewType extends BaseType
 {
     private $propertyAccessor;

     public function __construct(PropertyAccessorInterface $propertyAccessor = null)
     {
         $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
     }

     public function buildView(ViewBuilderInterface $builder, array $options)
     {
         parent::buildView($builder, $options);

         $isDataOptionSet = array_key_exists('data', $options);

         $builder
            ->setPropertyPath($options['property_path'])
            ->setMapped($options['mapped'])
            ->setInheritData($options['inherit_data'])
            ->setCompound($options['compound'])
            ->setData($isDataOptionSet ? $options['data'] : null)
            ->setDataLocked($isDataOptionSet)
            ->setDataMapper($options['compound'] ? new PropertyPathMapper($this->propertyAccessor) : null);
     }

     public function buildRenderingView(RenderingView $rview, ViewInterface $view, array $options)
     {
         parent::buildRenderingView($rview, $view, $options);

         $name = $view->getName();

         if ($rview->parent) {
             if ('' === $name) {
                 throw new LogicException('Form node with empty name can be used only as root form node.');
             }

             // Complex fields are read-only if they themselves or their parents are.
             if (!isset($rview->vars['attr']['readonly']) && isset($rview->parent->vars['attr']['readonly']) && false !== $rview->parent->vars['attr']['readonly']) {
                 $rview->vars['attr']['readonly'] = true;
             }
         }

         $rview->vars = array_replace($rview->vars, array(
            'value'      => $view->getViewData(),
            'data'       => $view->getData(),
            'label_attr' => $options['label_attr'],
            'compound'   => $view->getConfig()->getCompound(),
        ));
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

         // Derive "data_class" option from passed "data" object
         $dataClass = function (Options $options) {
             return isset($options['data']) && is_object($options['data']) ? get_class($options['data']) : null;
         };

         // If data is given, the form is locked to that data
         // (independent of its value)
         $resolver->setDefined(array('data'));

         $resolver->setDefaults(array(
            'data_class'              => $dataClass,
            'property_path'           => null,
            'mapped'                  => true,
            'label_attr'              => array(),
            'inherit_data'            => false,
            'compound'                => true,
            'attr'                    => array(),
        ));

         $resolver->setAllowedTypes('label_attr', 'array');
     }

     public function getBlockPrefix(): string
     {
         return 'view';
     }

     public function getParent()
     {
         return null;
     }
 }
