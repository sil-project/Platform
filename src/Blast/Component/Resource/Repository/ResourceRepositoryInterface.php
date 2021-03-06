<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\Resource\Repository;

use InvalidArgumentException;
use Blast\Component\Resource\Model\ResourceInterface;

/**
 * @author glenn
 */
interface ResourceRepositoryInterface
{
    /**
     * @param mixed $id
     *
     * @return ResourceInterface
     *
     * @throws InvalidArgumentException
     */
    public function get($id): ResourceInterface;

    /**
     * @param ResourceInterface $resource
     */
    public function add(ResourceInterface $resource): void;

    /**
     * @param array|ResourceInterface[] $resources
     */
    public function addAll(array $resources): void;

    /**
     * @param ResourceInterface $resource
     */
    public function remove(ResourceInterface $resource): void;

    /**
     * @param array|ResourceInterface[] $resources
     */
    public function removeAll(array $resources): void;

    /**
     * @param ResourceInterface $resource
     */
    public function update(ResourceInterface $resource): void;

    /**
     * @return string
     */
    public function getClassName(): string;
}
