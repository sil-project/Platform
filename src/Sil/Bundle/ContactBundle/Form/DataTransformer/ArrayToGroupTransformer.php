<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Sil\Bundle\ContactBundle\Entity\Group;

/**
 * Array to Group transformer.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class ArrayToGroupTransformer implements DataTransformerInterface
{
    /**
     * Transforms an Group instance into an array.
     *
     * @param  $group
     *
     * @return array
     */
    public function transform($group)
    {
        return $group;
    }

    /**
     * Transforms a data array into an Group instance.
     *
     * @param $data
     *
     * @return Group
     *
     * @throws TransformationFailedException
     */
    public function reverseTransform($data)
    {
        if ($data instanceof Group) {
            return $data;
        }

        if (!isset($data['name'])) {
            throw new TransformationFailedException('Property name is mandatory to construct a Group');
        }

        $group = new Group($data['name']);

        if (isset($data['parent'])) {
            $parent = $data['parent'];

            if ($parent) {
                $group->setParent($parent);
                $parent->addChild($group);
            }
        }

        return $group;
    }
}
