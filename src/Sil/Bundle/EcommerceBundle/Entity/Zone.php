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

namespace Sil\Bundle\EcommerceBundle\Entity;

use Sylius\Component\Addressing\Model\Zone as BaseZone;
use Blast\Bundle\OuterExtensionBundle\Entity\Traits\OuterExtensible;
/* @todo reference to AppBundle should be removed */
use AppBundle\Entity\OuterExtension\SilEcommerceBundle\ZoneExtension;

class Zone extends BaseZone
{
    use OuterExtensible,
    ZoneExtension;

    public function initZone()
    {
        $this->initOuterExtendedClasses();
    }

    public function __toString(): string
    {
        return (string) $this->getName();
    }

    /**
     * __clone().
     */
    public function __clone()
    {
        $this->id = null;
    }
}
