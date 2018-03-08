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

use Blast\Component\View\RenderingView;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
  * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
  */
 class JsonSerializer
 {
     public function serialize(RenderingView $rview)
     {
         $encoders = [new JsonEncoder()];
         $normalizers = [new RenderingViewNormalizer()];
         $serializer = new Serializer($normalizers, $encoders);

         return $serializer->serialize($rview, 'json');
     }
 }
