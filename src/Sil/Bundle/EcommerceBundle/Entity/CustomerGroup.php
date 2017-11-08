<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Entity;

use Sylius\Component\Customer\Model\CustomerGroup as BaseCustomerGroup;
use Doctrine\Common\Collections\ArrayCollection;

class CustomerGroup extends BaseCustomerGroup
{
    protected $customers;

    public function initCustomerGroup()
    {
        $this->customers = new ArrayCollection();
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
