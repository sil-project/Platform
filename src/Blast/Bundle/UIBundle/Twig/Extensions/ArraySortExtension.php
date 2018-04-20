<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UIBundle\Twig\Extensions;

use Traversable;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ArraySortExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                'blast_sort_by',
                [$this, 'sortBy'],
                [
                    'is_safe' => ['html'],
                ]
            ),
        ];
    }

    public function sortBy($collection, string $property, string $order = 'ASC'): array
    {
        $array = $this->toArray($collection);

        usort($array, function ($a, $b) use ($property, $order) {
            $propertyAccessor = PropertyAccess::createPropertyAccessor();

            $propertyA = (string) $propertyAccessor->getValue($a, $property);
            $propertyB = (string) $propertyAccessor->getValue($b, $property);

            $result = strcasecmp($propertyA, $propertyB);

            if ($order !== 'ASC') {
                $result = $result * -1;
            }

            return $result;
        });

        return $array;
    }

    private function toArray($collection): array
    {
        if (is_array($collection)) {
            return $collection;
        }

        if ($collection instanceof Traversable) {
            return iterator_to_array($collection);
        }

        return [];
    }
}
