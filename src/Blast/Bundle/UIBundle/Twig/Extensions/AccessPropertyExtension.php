<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UIBundle\Twig\Extensions;

use Symfony\Component\PropertyAccess\PropertyAccessor;

class AccessPropertyExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'blast_access_property',
                [$this, 'accessProperty'],
                [
                    'is_safe' => ['html'],
                ]
            ),
        ];
    }

    public function accessProperty($object, string $propertyPath)
    {
        $propertyAccessor = new PropertyAccessor();

        return $propertyAccessor->getValue($object, $propertyPath);
    }
}
