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

namespace Sil\Bundle\SonataSyliusUserBundle\Entity\Traits\OuterExtension;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;

trait HasUsers
{
    /**
     * @var Collection
     */
    protected $users;

    /**
     * @param UserInterface $user
     *
     * @return mixed
     */
    public function addUser(UserInterface $user)
    {
        $this->users->add($user);

        return $this;
    }

    /**
     * @param UserInterface $user
     *
     * @return mixed
     */
    public function removeUser(UserInterface $user)
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function initUsers()
    {
        $this->users = new ArrayCollection();
    }
}
