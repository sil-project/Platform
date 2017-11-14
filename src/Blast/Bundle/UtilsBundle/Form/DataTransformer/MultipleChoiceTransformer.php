<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UtilsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class MultipleChoiceTransformer implements DataTransformerInterface
{
    public function transform($choices)
    {
        return explode('||', $choices);
    }

    public function reverseTransform($choices)
    {
        return implode('||', $choices);
    }
}
