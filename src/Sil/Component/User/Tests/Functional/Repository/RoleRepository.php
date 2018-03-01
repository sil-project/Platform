<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\User\Tests\Functional\Repository;

use Sil\Component\User\Repository\RoleRepositoryInterface;
use Sil\Component\User\Model\Role;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class RoleRepository implements RoleRepositoryInterface
{
    public function getRoleHierarchy(): array
    {
        $admin = new Role('ROLE_ADMIN');
        $manager = new Role('ROLE_MANAGER', $admin);
        $employee = new Role('ROLE_EMPLOYEE', $manager);
        $intern = new role('ROLE_INTERN', $manager);
        $visitor = new Role('ROLE_VISITOR', $admin);
        $customer = new Role('ROLE_CUSTOMER', $visitor);

        return [$admin, $manager, $employee, $intern, $visitor, $customer];
    }
}
