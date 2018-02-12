<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Contact\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Blast\Component\Resource\Model\ResourceInterface;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class Group implements ResourceInterface, GroupInterface
{
    /**
     * name.
     *
     * @var string
     */
    protected $name;

    /**
     * members.
     *
     * @var Collection|GroupMemberInterface[]
     */
    protected $members;

    /**
     * parent group.
     *
     * @var GroupInterface
     */
    protected $parent;

    /**
     * child groups.
     *
     * @var Collection|GroupInterface[]
     */
    protected $children;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->members = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    /**
     * Get the value of name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name.
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the value of members as an array.
     *
     * @return array|GroupMemberInterface[]
     */
    public function getMembers(): array
    {
        return $this->members->toArray();
    }

    /**
     * Add a member to the collection.
     *
     * @param GroupMemberInterface $member
     */
    public function addMember(GroupMemberInterface $member)
    {
        if ($this->members->contains($member)) {
            throw new \InvalidArgumentException('This entity is already a member of this group');
        }

        $this->members->add($member);
    }

    /**
     *  Remove a member from the collection.
     *
     * @param GroupMemberInterface $member
     */
    public function removeMember(GroupMemberInterface $member)
    {
        if (!$this->hasMember($member)) {
            throw new \InvalidArgumentException('Trying to remove a member that is not in that group');
        }

        $this->members->removeElement($member);
    }

    /**
     * Check if a Member exists in the Collection.
     *
     * @param GroupMemberInterface $member
     *
     * @return bool whether the Member exists
     */
    public function hasMember(GroupMemberInterface $member)
    {
        return $this->members->contains($member);
    }

    /**
     * Get the value of parent.
     *
     * @return GroupInterface|null
     */
    public function getParent(): ?GroupInterface
    {
        return $this->parent;
    }

    /**
     * Set the value of parent.
     *
     * @param GroupInterface $parent
     */
    public function setParent(GroupInterface $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * Get the value of child groups as an array.
     *
     * @return array|GroupInterface[]
     */
    public function getChildren(): array
    {
        return $this->children->toArray();
    }

    /**
     * Add a child group to the collection.
     *
     * @param GroupInterface $child
     */
    public function addChild(GroupInterface $child)
    {
        if ($this->children->contains($child)) {
            throw new \InvalidArgumentException('This entity is already a child of this group');
        }

        $this->children->add($child);
    }

    /**
     *  Remove a child group from the collection.
     *
     * @param GroupInterface $child
     */
    public function removeChild(GroupInterface $child)
    {
        if (!$this->hasChild($child)) {
            throw new \InvalidArgumentException('Trying to remove a non existing child group');
        }

        $this->children->removeElement($child);
    }

    /**
     * Check if a child group exists in the Collection.
     *
     * @param GroupChildInterface $child
     *
     * @return bool whether the child exists
     */
    public function hasChild(GroupInterface $child)
    {
        return $this->children->contains($child);
    }
}
