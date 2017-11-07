<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\UtilsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class CustomChoiceTransformer implements DataTransformerInterface
{
    private $repo;

    public function __construct($repo)
    {
        $this->repo = $repo;
    }

    public function transform($choices)
    {
        if (null !== $choices) {
            $choice = $this->repo->find($choices);

            return $choice;
        }

        return $choices;
    }

    public function reverseTransform($choices)
    {
        return $choices;
    }
}
