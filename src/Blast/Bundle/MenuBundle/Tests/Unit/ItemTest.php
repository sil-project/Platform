<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\MenuBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Blast\Bundle\MenuBundle\Model\Item;

class ItemTest extends TestCase
{
    private $itemName = 'test_item';
    private $item;

    protected function setUp()
    {
        $this->item = new Item($this->itemName);
    }

    public function testLabelIsSameThanIdIfNotSetted()
    {
        $this->assertEquals($this->item->getId(), $this->item->getLabel());
    }

    public function testLabelDifferentThanIdIfSetted()
    {
        $customLabel = $this->itemName . '_custom_label';

        $oldLabel = $this->item->getLabel();

        $this->item->setLabel($customLabel);

        $this->assertNotSame($this->item->getId(), $this->item->getLabel());
        $this->assertEquals($this->item->getLabel(), $customLabel);
    }

    public function testItemRoleHasAsKeyTheSameValueAsValue()
    {
        $role = 'ROLE_TEST';

        $this->item->addRole($role);

        $this->assertArrayHasKey($role, $this->item->getRoles());
        $this->assertEquals($role, $this->item->getRoles()[$role]);
    }

    public function testGetRootOnRootItem()
    {
        $root = $this->item->getRoot();

        $this->assertNotNull($root);
        $this->assertInstanceOf(Item::class, $root);
        $this->assertEquals($root->getId(), $this->itemName);
    }

    public function testGetParentOnRootItem()
    {
        $parent = $this->item->getParent();

        $this->assertNull($parent);
    }

    public function testGetRootOnChildItem()
    {
        $childName = 'test_child';

        $child = new Item($childName);
        $this->item->addChild($child);

        $root = $this->item->getRoot();

        $this->assertNotNull($root);
        $this->assertInstanceOf(Item::class, $root);
        $this->assertEquals($root->getId(), $this->itemName);
    }

    public function testGetParentOnChildItem()
    {
        $childName = 'test_child';

        $child = new Item($childName);
        $this->item->addChild($child);

        $parent = $child->getParent();

        $this->assertNotNull($parent);
        $this->assertInstanceOf(Item::class, $parent);
        $this->assertEquals($parent->getId(), $this->itemName);
    }

    public function testAddChildTwice()
    {
        $childName = 'test_child';

        $child1 = new Item($childName);
        $this->item->addChild($child1);

        $child2 = new Item($childName);
        $this->item->addChild($child2);

        $this->assertCount(1, $this->item->getChildren());
    }

    public function testAddChildMultipleTimes()
    {
        $childName = 'test_child_';
        $childNumber = 10;

        for ($i = 0; $i < $childNumber; $i++) {
            $child = new Item($childName . $i);
            $this->item->addChild($child);
        }

        $this->assertCount($childNumber, $this->item->getChildren());
    }

    public function testRemoveInexistingChild()
    {
        $childName = 'test_child';
        $orphanItemName = 'item_orphan';

        $child = new Item($childName);
        $this->item->addChild($child);

        $orphanItem = new Item($orphanItemName);

        $this->item->removeChild($orphanItem);

        $this->assertCount(1, $this->item->getChildren());
    }

    public function testItemToArray()
    {
        $output = [
            'test_item' => [
                'label'    => 'test_item',
                'icon'     => null,
                'route'    => null,
                'order'    => null,
                'display'  => true,
                'roles'    => [],
                'parent'   => null,
                'children' => [],
            ],
        ];

        $this->assertEquals($output, $this->item->toArray());
    }
}
