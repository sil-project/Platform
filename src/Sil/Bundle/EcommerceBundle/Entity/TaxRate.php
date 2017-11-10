<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Entity;

use Sylius\Component\Core\Model\TaxRate as BaseTaxRate;

class TaxRate extends BaseTaxRate
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __toString(): string
    {
        return (string) sprintf('%s (%s)', $this->getName(), $this->getCode());
    }

    /**
     * __clone().
     */
    public function __clone()
    {
        $this->id = null;
    }
}
