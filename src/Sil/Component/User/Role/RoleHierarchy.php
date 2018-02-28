<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\User\Role;

use Symfony\Component\Security\Core\Role\RoleHierarchy as BaseRoleHierarchy;
use Sil\Component\User\Model\RoleInterface;
use Sil\Component\User\Repository\RoleRepositoryInterface;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class RoleHierarchy extends BaseRoleHierarchy
{
    /**
     * RoleHierarchy constructor.
     *
     * @param RoleRepositoryInterface
     */
    public function __construct(RoleRepositoryInterface $repository)
    {
        $hierarchy = $this->buildHierarchy($repository->getRoleHierarchy());

        parent::__construct($hierarchy);
    }

    /**
     * Build role hierarchy (array of string representations) from Role objects.
     *
     * @param array|RoleInterface[]
     *
     * @return array
     */
    protected function buildHierarchy(array $roles)
    {
        $hierarchy = [];

        foreach ($roles as $role) {
            if ($role instanceof RoleInterface) {
                if ($role->getParent()) {
                    $hierarchy[$role->getParent()->getRole()][] = $role->getRole();
                } else {
                    if (!isset($hierarchy[$role->getRole()])) {
                        $hierarchy[$role->getRole()] = [];
                    }
                }
            }
        }

        return $hierarchy;
    }
}
