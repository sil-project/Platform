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

interface NestedItemInterface
{
    /**
     * @return array
     */
    public function getChildren(): array;

    /**
     * @param array $children
     */
    public function setChildren(array $children): void;

    /**
     * @param string $childId
     *
     * @return ItemInterface
     */
    public function getChild(string $childId): ?ItemInterface;

    /**
     * @param ItemInterface $child
     */
    public function addChild(ItemInterface $child): void;

    /**
     * @param ItemInterface $child
     */
    public function removeChild(ItemInterface $child): void;

    /**
     * @return ItemInterface
     */
    public function getParent(): ?ItemInterface;

    /**
     * @param ItemInterface $parent
     */
    public function setParent(ItemInterface $parent): void;

    /**
     * @return ItemInterface
     */
    public function getRoot(): ItemInterface;
}
