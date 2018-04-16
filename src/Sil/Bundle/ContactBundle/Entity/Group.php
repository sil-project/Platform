<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Entity;

use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\NestedTreeable;
use Sil\Component\Contact\Model\Group as BaseGroup;
use Sil\Component\Contact\Model\GroupInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Group.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class Group extends BaseGroup
{
    use Guidable;
    use NestedTreeable;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->treeChildren = new ArrayCollection();

        parent::__construct($name);
    }

    /**
     * Get the value of parent.
     *
     * @return GroupInterface|null
     */
    public function getParent(): ?GroupInterface
    {
        return $this->treeParent;
    }

    /**
     * Set the value of parent.
     *
     * @param GroupInterface $parent
     */
    public function setParent(GroupInterface $parent = null): void
    {
        $this->treeParent = $parent;
    }

    /**
     * Get the value of child groups as an array.
     *
     * @return array|GroupInterface[]
     */
    public function getChildren(): array
    {
        if ($this->treeChildren) {
            return $this->treeChildren->toArray();
        }

        return [];
    }

    /**
     * Add a child group to the collection.
     *
     * @param GroupInterface $child
     */
    public function addChild(GroupInterface $child)
    {
        if ($this->treeChildren->contains($child)) {
            throw new \InvalidArgumentException('This entity is already a child of this group');
        }

        $this->treeChildren->add($child);
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

        $this->treeChildren->removeElement($child);
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
        return $this->treeChildren->contains($child);
    }
}
