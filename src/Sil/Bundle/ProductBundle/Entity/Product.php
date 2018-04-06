<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ProductBundle\Entity;

use Sil\Component\Product\Model\Product as BaseProduct;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Descriptible;

class Product extends BaseProduct
{
    use
        Guidable,
        Descriptible
    ;

    /**
     * {@inheritdoc}
     */
    public function getOptionTypes(): array
    {
        $iterator = $this->optionTypes->getIterator();

        $iterator->uasort(function ($first, $second) {
            return $first->getName() > $second->getName() ? 1 : -1;
        });

        return $iterator->getArrayCopy();
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes(): array
    {
        $iterator = $this->attributes->getIterator();

        $iterator->uasort(function ($first, $second) {
            return $first->getAttributeType()->getName() > $second->getAttributeType()->getName() ? 1 : -1;
        });

        return $iterator->getArrayCopy();
    }

    public function getReusableAttributes(): array
    {
        return $this->attributes->filter(function ($attribute) {
            return $attribute->isReusable();
        })->toArray();
    }

    public function getNonReusableAttributes(): array
    {
        return $this->attributes->filter(function ($attribute) {
            return !$attribute->isReusable();
        })->toArray();
    }
}
