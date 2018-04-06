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

use Sil\Component\Product\Model\AttributeType as BaseAttributeType;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;

class AttributeType extends BaseAttributeType
{
    use Guidable;

    public function getValuesCount(): int
    {
        return $this->attributes->count();
    }

    /**
     * Check if this AttributeType can be deleted.
     *
     * @return bool
     */
    public function canBeDeleted(): bool
    {
        $deleteAllowed = true;
        if (count($this->getAttributes()) > 0) {
            foreach ($this->getAttributes() as $attr) {
                if (count($attr->getProducts()) > 0) {
                    $deleteAllowed = false;
                }
            }
        }

        return $deleteAllowed;
    }

    /**
     * Gets the list of deletable AttributeType attributes.
     *
     * @return array
     */
    public function getDeletableAttributes(): array
    {
        $deleteAttributes = [];
        if (count($this->getAttributes()) > 0) {
            foreach ($this->getAttributes() as $attr) {
                if (count($attr->getProducts()) == 0) {
                    $deleteAttributes[] = $attr;
                }
            }
        }

        return $deleteAttributes;
    }
}
