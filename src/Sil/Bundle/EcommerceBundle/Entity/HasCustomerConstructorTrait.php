<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\EcommerceBundle\Entity\OuterExtension;

use Doctrine\Common\Collections\ArrayCollection;

trait HasCustomerConstructorTrait
{
    /**
     * Will be called by OuterExtensible::initOuterExtendedClasses();.
     */
    protected function initCustomerConstructor()
    {
        $this->orders = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->oauthAccounts = new ArrayCollection();
        $this->createdAt = new \DateTime();

        // Set here to overwrite default value from trait
        $this->enabled = false;
    }
}
