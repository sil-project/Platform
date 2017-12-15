<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Entity\Association;

use Sylius\Component\Core\Model\ShopUserInterface;

trait HasShopUserTrait
{
    /**
     * @var ShopUserInterface
     */
    protected $user;

    /**
     * @return ShopUserInterface
     */
    public function getUser(): ?ShopUserInterface
    {
        return $this->user;
    }

    /**
     * @param ShopUserInterface $user
     */
    public function setUser(ShopUserInterface $user): void
    {
        $this->user = $user;
    }
}
