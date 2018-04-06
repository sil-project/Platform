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

use Sil\Component\Product\Model\OptionType as BaseOptionType;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;

class OptionType extends BaseOptionType
{
    use Guidable;

    public function getValuesCount(): int
    {
        return $this->options->count();
    }

    /**
     * Check if this OptionType can be deleted.
     *
     * @return bool
     */
    public function canBeDeleted(): bool
    {
        $deleteAllowed = true;
        if (count($this->getOptions()) > 0) {
            foreach ($this->getOptions() as $opt) {
                if (count($opt->getProductVariants()) > 0) {
                    $deleteAllowed = false;
                }
            }
        }

        return $deleteAllowed;
    }

    /**
     * Gets the list of deletable OptionType options.
     *
     * @return array
     */
    public function getDeletableOptions(): array
    {
        $deleteAttributes = [];
        if (count($this->getOptions()) > 0) {
            foreach ($this->getOptions() as $attr) {
                if (count($attr->getProductVariants()) == 0) {
                    $deleteAttributes[] = $attr;
                }
            }
        }

        return $deleteAttributes;
    }
}
