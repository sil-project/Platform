<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\MenuBundle\Model;

interface RolableItemInterface
{
    /**
     * @return array
     */
    public function getRoles(): array;

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void;

    /**
     * @param string $role
     */
    public function addRole(string $role): void;

    /**
     * @param string $role
     */
    public function removeRole(string $role): void;
}
