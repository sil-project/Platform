<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Contact\Model\Traits;

use Sil\Component\Contact\Model\GroupInterface;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
trait GroupMemberTrait
{
    /**
     * groups.
     *
     * @var Collection|GroupInterface
     */
    protected $groups;

    /**
     * Get the value of groups.
     *
     * @return array|GroupInterface[]
     */
    public function getGroups(): array
    {
        return $this->groups->toArray();
    }

    /**
     * Add a Group to the collection.
     *
     * @param GroupInterface $group
     */
    public function addGroup(GroupInterface $group)
    {
        if ($this->groups->contains($group)) {
            throw new \InvalidArgumentException('This contact is already a member of this group');
        }

        $this->groups->add($group);
    }

    /**
     *  Remove a Group from the collection.
     *
     * @param GroupInterface $group
     */
    public function removeGroup(GroupInterface $group)
    {
        if (!$this->isMemberOf($group)) {
            throw new \InvalidArgumentException('Trying to remove a non existing group');
        }

        $this->groups->removeElement($group);
    }

    /**
     * Check if a Group exists in the Collection.
     *
     * @param GroupInterface $group
     *
     * @return bool whether the Group exists
     */
    public function isMemberOf(GroupInterface $group)
    {
        return $this->groups->contains($group);
    }
}
