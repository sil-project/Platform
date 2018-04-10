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
     * Finds an object by its primary key / identifier.
     *
     * @param mixed $id the identifier
     *
     * @return ResourceInterface|null the object
     */
    public function find($id): ?ResourceInterface;

    /**
     * Finds all objects in the repository.
     *
     * @return array|ResourceInterface[] the objects
     */
    public function findAll(): array;

    /**
     * Finds objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return array|ResourceInterface the objects
     *
     * @throws \UnexpectedValueException
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array;

    /**
     * Finds a single object by a set of criteria.
     *
     * @param array $criteria the criteria
     *
     * @return ResourceInterface|null the object
     */
    public function findOneBy(array $criteria): ?ResourceInterface;

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
