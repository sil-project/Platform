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

namespace Librinfo\EcommerceBundle\Entity;

/* @todo reference to AppBundle should be removed */
use AppBundle\Entity\OuterExtension\LibrinfoEcommerceBundle\ProductOptionValueExtension;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use Sylius\Component\Product\Model\ProductOptionValue as BaseProductOptionValue;

class ProductOptionValue extends BaseProductOptionValue
{
    use OuterExtensible, ProductOptionValueExtension;

    /**
     * @return string "Option name: OptionValue value"
     */
    public function getFullName(): string
    {
        return sprintf('%s: %s', $this->option->getName(), $this->getValue());
    }

    /**
     * @return string "Option code: OptionValue code"
     */
    public function getFullCode()
    {
        return sprintf('%s: %s', $this->option->getCode(), $this->getCode());
    }
}
