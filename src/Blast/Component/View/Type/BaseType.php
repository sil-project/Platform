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

use Symfony\Component\Form\Util\StringUtil;
use Blast\Component\View\ViewInterface;
use Blast\Component\View\RenderingView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
  * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
  */
 class BaseType extends AbstractType
 {
     public function buildRenderingView(RenderingView $rview, ViewInterface $view, array $options)
     {
         parent::buildRenderingView($rview, $view, $options);

         $name = $view->getName();
         $blockName = $options['block_name'] ?: $view->getName();
         $translationDomain = $options['translation_domain'];
         $labelFormat = $options['label_format'];

         if ($rview->parent) {
             if ('' !== ($parentFullName = $rview->parent->vars['full_name'])) {
                 $id = sprintf('%s_%s', $rview->parent->vars['id'], $name);
                 $fullName = sprintf('%s[%s]', $parentFullName, $name);
                 $uniqueBlockPrefix = sprintf('%s_%s', $rview->parent->vars['unique_block_prefix'], $blockName);
             } else {
                 $id = $name;
                 $fullName = $name;
                 $uniqueBlockPrefix = '_' . $blockName;
             }

             if (null === $translationDomain) {
                 $translationDomain = $rview->parent->vars['translation_domain'];
             }

             if (!$labelFormat) {
                 $labelFormat = $rview->parent->vars['label_format'];
             }
         } else {
             $id = $name;
             $fullName = $name;
             $uniqueBlockPrefix = '_' . $blockName;

             // Strip leading underscores and digits. These are allowed in
             // form names, but not in HTML4 ID attributes.
             // http://www.w3.org/TR/html401/struct/global.html#adef-id
             $id = ltrim($id, '_0123456789');
         }

         $blockPrefixes = array();
         for ($type = $view->getConfig()->getType(); null !== $type; $type = $type->getParent()) {
             array_unshift($blockPrefixes, $type->getBlockPrefix());
         }
         $blockPrefixes[] = $uniqueBlockPrefix;

         $rview->vars = array_replace($rview->vars, array(
            'view'                => $rview,
            'id'                  => $id,
            'name'                => $name,
            'full_name'           => $fullName,
            'label'               => $options['label'],
            'label_format'        => $labelFormat,
            'attr'                => $options['attr'],
            'block_prefixes'      => $blockPrefixes,
            'unique_block_prefix' => $uniqueBlockPrefix,
            'translation_domain'  => $translationDomain,
            // Using the block name here speeds up performance in collection
            // forms, where each entry has the same full block name.
            // Including the type is important too, because if rows of a
            // collection form have different types (dynamically), they should
            // be rendered differently.
            // https://github.com/symfony/symfony/issues/5038
            'cache_key' => $uniqueBlockPrefix . '_' . $view->getConfig()->getType()->getBlockPrefix(),
        ));
     }

     public function configureOptions(OptionsResolver $resolver)
     {
         parent::configureOptions($resolver);

         $resolver->setDefaults(array(
                 'block_name'         => null,
                 'label'              => null,
                 'label_format'       => null,
                 'attr'               => array(),
                 'translation_domain' => null,
             ));

         $resolver->setAllowedTypes('attr', 'array');
     }

     public function getBlockPrefix(): string
     {
         return StringUtil::fqcnToBlockPrefix(get_class($this));
     }
 }
