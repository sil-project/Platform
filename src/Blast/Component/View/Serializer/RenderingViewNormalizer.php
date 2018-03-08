<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\View\Serializer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
  * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
  */
 class RenderingViewNormalizer implements NormalizerInterface
 {
     public function normalize($object, $format = null, array $context = [])
     {
         $data = [];

         if (!$object->vars['compound']) {
             return $object->vars['value'];
         }

         foreach ($object->children as $child) {
             $data[$child->vars['name']] = $this->normalize($child);
         }

         return $data;
     }

     public function supportsNormalization($data, $format = null)
     {
         return true;
     }
 }
