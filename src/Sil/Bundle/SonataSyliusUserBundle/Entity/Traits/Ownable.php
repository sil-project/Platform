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

namespace Librinfo\SonataSyliusUserBundle\Entity\Traits;

use Symfony\Component\Security\Core\User\UserInterface;

trait Ownable
{
    /**
     * @var UserInterface
     */
    private $owner = null;

    /**
     * @return UserInterface
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param UserInterface $owner
     *
     * @return self
     */
    public function setOwner(UserInterface $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }
}
