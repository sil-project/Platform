<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\User\Model;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class Role implements RoleInterface
{
    /**
     * name.
     *
     * @var string
     */
    protected $role;

    /**
     * parent role.
     *
     * @var RoleInterface
     */
    protected $parent;

    /**
     * children roles.
     *
     * @var Collection|RoleInterface[]
     */
    protected $children;

    /**
     * @param string        $role
     * @param roleInterface $parent
     */
    public function __construct(string $role, RoleInterface $parent = null)
    {
        $this->role = $role;
        $this->parent = $parent;
        $this->children = new ArrayCollection();
    }

    /**
     * Get the value of role.
     *
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Set the value of name.
     *
     * @param string $name
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * Get parent role.
     *
     * @return RoleInterface|null
     */
    public function getParent(): ?RoleInterface
    {
        return $this->parent;
    }

    /**
     * Set parent role.
     *
     * @param RoleInterface $parent
     */
    public function setParent(RoleInterface $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * Get children roles.
     *
     * @return array|RoleInterface[]
     */
    public function getChildren(): array
    {
        return $this->children->getValues();
    }

    /**
     * Add a child to the collection.
     *
     * @param RoleInterface $child
     */
    public function addChild(RoleInterface $child)
    {
        if ($this->children->contains($child)) {
            throw new \InvalidArgumentException('This role already owns this child');
        }

        $this->children->add($child);
    }

    /**
     *  Remove a child from the collection.
     *
     * @param RoleInterface $child
     */
    public function removeChild(RoleInterface $child)
    {
        if (!$this->hasChild($child)) {
            throw new \InvalidArgumentException('Trying to remove a child that does not belong to this role');
        }

        $this->children->removeElement($child);
    }

    /**
     * Check if a child exists in the Collection.
     *
     * @param RoleInterface $child
     *
     * @return bool whether the child exists
     */
    public function hasChild(RoleInterface $child)
    {
        return $this->children->contains($child);
    }
}
