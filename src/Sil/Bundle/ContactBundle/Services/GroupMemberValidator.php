<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Services;

use Sil\Component\Contact\Model\GroupInterface;
use Sil\Component\Contact\Model\GroupMemberInterface;
use Sil\Component\Contact\Repository\GroupRepositoryInterface;

/**
 * Validate group member additions.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class GroupMemberValidator
{
    /**
     * Group repository.
     *
     * @var GroupRepositoryInterface
     */
    private $repository;

    /**
     * @param ArrayToAddressTransformer $transformer
     */
    public function __construct(GroupRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function isValidMember(GroupInterface $group, GroupMemberInterface $member): bool
    {
        $parents = $this->repository->getPath($group);
        $children = $this->repository->getNodesHierarchyQuery($group)->execute();

        foreach (array_merge($parents, $children) as $key => $group) {
            if ($member->isMemberOf($group)) {
                return false;
            }
        }

        return true;
    }
}
