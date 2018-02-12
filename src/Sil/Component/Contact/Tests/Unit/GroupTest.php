<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Group\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sil\Component\Contact\Model\Contact;
use Sil\Component\Contact\Model\Group;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 *
 * @coversDefaultClass \Sil\Component\Contact\Model\Group
 */
class GroupTest extends TestCase
{
    /**
     * An exception should be thrown when adding a member
     * that is already in the group.
     */
    public function test_add_already_existing_member()
    {
        $group = new Group('foo');
        $contact = new Contact();

        $group->addMember($contact);

        $this->expectException(\InvalidArgumentException::class);

        $group->addMember($contact);
    }

    /**
     * An exception should be thrown when trying to remove a member that is not in the group.
     */
    public function test_remove_non_existing_member()
    {
        $group = new Group('foo');
        $contact1 = new Contact();
        $contact2 = new Contact();

        $group->addMember($contact1);

        $this->expectException(\InvalidArgumentException::class);

        $group->removeMember($contact2);
    }

    /**
     * An exception should be thrown when adding a group
     * to a group that is already its parent.
     */
    public function test_add_already_existing_child()
    {
        $group = new Group('foo');
        $child = new Group('bar');

        $group->addChild($child);

        $this->expectException(\InvalidArgumentException::class);

        $group->addChild($child);
    }

    /**
     * An exception should be thrown when trying to remove a child group that
     * does not have the targeted group as parent.
     */
    public function test_remove_non_existing_child()
    {
        $group = new Group('foo');
        $child = new Group('bar');
        $child2 = new Group('baz');

        $group->addChild($child);

        $this->expectException(\InvalidArgumentException::class);

        $group->removeChild($child2);
    }
}
