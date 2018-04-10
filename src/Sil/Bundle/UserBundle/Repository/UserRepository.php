<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\UserBundle\Repository;

use Sil\Component\User\Repository\UserRepositoryInterface;
use Blast\Bundle\ResourceBundle\Doctrine\ORM\Repository\ResourceRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends ResourceRepository implements UserRepositoryInterface, UserLoaderInterface
{
    /* Symfony UserLoaderInterface */
    public function loadUserByUsername($username)
    {
        return $this->findOneUserByUsername($username);
    }

    public function findOneUserByUsername($username)
    {
        return $this->findOneBy(['username' => $username]);
    }
}
