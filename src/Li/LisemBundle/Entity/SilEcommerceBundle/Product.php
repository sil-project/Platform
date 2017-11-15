<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\Entity\SilEcommerceBundle;

use Sil\Bundle\EcommerceBundle\Entity\Product as BaseProduct;
use Sil\Bundle\VarietyBundle\Entity\Association\HasVarietyTrait;

class Product extends BaseProduct
{
    use HasVarietyTrait;

    public static $PACKAGING_OPTION_CODE = '_lisem_packaging';

    /**
     * @param string $optionCode
     *
     * @return bool
     */
    public function hasOptionByCode($optionCode)
    {
        foreach ($this->options as $option) {
            if ($option->getCode() === $optionCode) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasPackagingOption()
    {
        return $this->hasOptionByCode(self::$PACKAGING_OPTION_CODE);
    }
}
