<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class ArrayCollectionTransformer implements DataTransformerInterface
{
    /**
     * reverseTransform : from array to ArrayCollection.
     *
     * @param array $value
     *
     * @return ArrayCollection
     */
    public function reverseTransform($value)
    {
        if ($value === null) {
            $value = [];
        }

        $arrayCollection = new ArrayCollection();

        foreach ($value as $v) {
            $arrayCollection->add($v);
        }

        return $arrayCollection;
    }

    /**
     * transform : from ArrayCollection to array.
     *
     * @param ArrayCollection $value
     *
     * @return array
     */
    public function transform($value)
    {
        if ($value === null) {
            $value = new ArrayCollection();
        }

        return $value->toArray();
    }
}
