<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\User\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sil\Component\User\Model\Role;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 *
 * @coversDefaultClass \Sil\Component\User\Model\Role
 */
class RoleTest extends TestCase
{
    /**
     * An exception should be thrown when adding a child
     * that is already in the role.
     */
    public function test_add_already_existing_child()
    {
        $role = new Role('foo');
        $child = new Role('bar');

        $role->addChild($child);

        $this->expectException(\InvalidArgumentException::class);

        $role->addChild($child);
    }

    /**
     * An exception should be thrown when trying to remove a child that is not in the role.
     */
    public function test_remove_non_existing_child()
    {
        $role = new Role('foo');
        $child1 = new Role('bar');
        $child2 = new Role('baz');

        $role->addChild($child1);

        $this->expectException(\InvalidArgumentException::class);

        $role->removeChild($child2);
    }
}
