<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Form\DataTransformer;

class PriceCentsTransformer implements \Symfony\Component\Form\DataTransformerInterface
{
    /**
     * reverseTransform : from currency format to cents.
     *
     * @param type $value
     *
     * @return type
     */
    public function reverseTransform($value)
    {
        return (int) (str_replace(',', '.', $value) * 100);
    }

    /**
     * transform : from cents to currency format.
     *
     * @param type $value
     *
     * @return type
     */
    public function transform($value)
    {
        return number_format((float) $value / 100, 2, '.', '');
    }
}
