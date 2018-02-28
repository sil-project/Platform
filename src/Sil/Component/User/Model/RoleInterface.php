<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\User\Model;

use Symfony\Component\Security\Core\Role\RoleInterface as BaseRoleInterface;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
interface RoleInterface extends BaseRoleInterface
{
    /**
     * Get parent role.
     *
     * @return RoleInterface
     */
    public function getParent(): ?self;

    /**
     * Get children roles.
     *
     * @return array|RoleInterface[]
     */
    public function getChildren(): array;
}
