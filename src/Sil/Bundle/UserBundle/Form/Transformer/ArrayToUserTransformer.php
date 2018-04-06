<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\UserBundle\Form\Transformer;

use Sil\Bundle\UserBundle\Form\Transformer\Exception\TransformationFailedException;
use Sil\Component\User\Model\UserInterface;
use Sil\Component\User\Repository\UserRepositoryInterface;
use Symfony\Component\Form\DataTransformerInterface;

class ArrayToUserTransformer implements DataTransformerInterface
{
    /**
     * @var string
     */
    protected $userClass;

    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var array
     */
    protected $mandatoryFields = ['username', 'password', 'email'];

    public function transform($value): array
    {
        /* hum not sure why set this to null when value is not an User ... */
        if (!$value instanceof UserInterface) {
            $user = [
                'id'          => null,
                'name'        => null,
                'password'    => null,
                'email'       => null,
                'enabled'     => null,
            ];
        } else {
            $user = [
                'id'          => $value->getId(),
                'username'    => $value->getUsername(),
                'password'    => $value->getPassword(),
                'email'       => $value->getEmail(),
                'enabled'     => $value->IsEnabled(),
            ];
        }

        return $user;
    }

    public function reverseTransform($value): UserInterface
    {
        if (isset($value['id'])) {
            $user = $this->userRepository->get($value['id']);
        } else {
            if (count(array_intersect(array_keys($value), $this->mandatoryFields)) != count($this->mandatoryFields)) {
                throw new TransformationFailedException(
                    UserInterface::class,
                    $value,
                    $this->mandatoryFields
                );
            }
            $user = new $this->userClass();
            $user->setUsername($value['username']);
            $user->setPassword($value['password']);
            $user->setEmail($value['email']);
        }
        $user->setEnabled(true);

        return $user;
    }

    /**
     * @param string $userClass
     */
    public function setUserClass(string $userClass): void
    {
        $this->userClass = $userClass;
    }

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function setUserRepository(UserRepositoryInterface $userRepository): void
    {
        $this->userRepository = $userRepository;
    }
}
