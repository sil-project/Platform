<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Entity\Association;

use Sil\Bundle\EcommerceBundle\Entity\CustomerGroup;

trait HasCustomerGroupTrait
{
    /**
     * @var CustomerGroup
     */
    protected $group;

    /**
     * @return CustomerGroup
     */
    public function getGroup(): ?CustomerGroup
    {
        return $this->group;
    }

    /**
     * @param CustomerGroup $group
     */
    public function setGroup(CustomerGroup $group): void
    {
        $this->group = $group;
    }
}
