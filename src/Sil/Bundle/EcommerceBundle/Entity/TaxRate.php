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

use Sylius\Component\Core\Model\TaxRate as BaseTaxRate;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
/* @todo reference to AppBundle should be removed */
use AppBundle\Entity\OuterExtension\LibrinfoEcommerceBundle\TaxRateExtension;

class TaxRate extends BaseTaxRate
{
    use OuterExtensible,
    TaxRateExtension;

    public function __construct()
    {
        parent::__construct();
        $this->initTaxRate();
    }

    public function initTaxRate()
    {
        $this->initOuterExtendedClasses();
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
