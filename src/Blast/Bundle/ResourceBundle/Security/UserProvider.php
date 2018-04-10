<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\ResourceBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Blast\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class UserProvider implements UserProviderInterface
{
    private $userRepository;

    public function __construct($userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loadUserByUsername($username)
    {
        $user = $this->userRepository->findBy(['login' => $username]);

        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof ResourceInterface) {
            throw new UnsupportedUserException(
                  sprintf('Instances of "%s" are not supported.', get_class($user))
              );
        }

        $id = $user->getId();
        $refreshedUser = $this->userRepository->find($id);
        if (null === $refreshedUser) {
            throw new UsernameNotFoundException(sprintf('User with id %s not found', json_encode($id)));
        }

        return $refreshedUser;
    }

    public function supportsClass($class)
    {
        return is_subclass_of($class, ResourceInterface::class);
    }
}
