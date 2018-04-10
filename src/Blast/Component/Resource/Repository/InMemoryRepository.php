<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\Resource\Repository;

use ArrayObject;
use Blast\Component\Resource\Model\ResourceInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class InMemoryRepository implements ResourceRepositoryInterface
{
    const ORDER_ASCENDING = 'ASC';
    const ORDER_DESCENDING = 'DESC';

    /**
     * @var string
     */
    protected $interface;

    /**
     * @var PropertyAccessor
     */
    protected $accessor;

    /**
     * @var ArrayObject
     */
    protected $arrayObject;

    public function __construct($interface)
    {
        $this->interface = $interface;
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->arrayObject = new ArrayObject();
    }

    public function get($id): ResourceInterface
    {
        $resource = $this->find($id);
        if (null == $resource) {
            throw new \InvalidArgumentException('Resource does not exist');
        }

        return $resource;
    }

    public function find($id): ?ResourceInterface
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findAll(): array
    {
        return $this->arrayObject->getArrayCopy();
    }

    public function addAll(array $resources): void
    {
        foreach ($resources as $resource) {
            $this->add($resource);
        }
    }

    public function add(ResourceInterface $resource): void
    {
        if (!$resource instanceof $this->interface) {
            throw new \InvalidArgumentException(
                'The added resource must be an instance of ' . $this->interface
            );
        }
        if (in_array($resource, $this->findAll())) {
            throw new \InvalidArgumentException('Resource already exists and cannot be added');
        }
        $this->arrayObject->append($resource);
    }

    public function removeAll(array $resources): void
    {
        foreach ($resources as $resource) {
            $this->remove($resource);
        }
    }

    public function remove(ResourceInterface $resource): void
    {
        $newResources = array_filter(
            $this->findAll(),
            function ($object) use ($resource) {
                return $object !== $resource;
            }
        );
        $this->arrayObject->exchangeArray($newResources);
    }

    public function update(ResourceInterface $resource): void
    {
        //does nothing
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        $results = $this->findAll();
        if (!empty($criteria)) {
            $results = $this->applyCriteria($results, $criteria);
        }
        if (!empty($orderBy)) {
            $results = $this->applyOrder($results, $orderBy);
        }
        $results = array_slice($results, ($offset ? $offset : 0), $limit);

        return $results;
    }

    public function findOneBy(array $criteria): ?ResourceInterface
    {
        if (empty($criteria)) {
            throw new \InvalidArgumentException('The criteria array needs to be set.');
        }
        $results = $this->applyCriteria($this->findAll(), $criteria);
        if ($result = reset($results)) {
            return $result;
        }

        return null;
    }

    public function getClassName(): string
    {
        return $this->interface;
    }

    private function applyCriteria(array $resources, array $criteria)
    {
        foreach ($this->arrayObject as $object) {
            foreach ($criteria as $criterion => $value) {
                if ($value !== $this->accessor->getValue($object, $criterion)) {
                    $key = array_search($object, $resources);
                    if ($key !== false) {
                        unset($resources[$key]);
                    }
                }
            }
        }

        return $resources;
    }

    protected function applyOrder(array $resources, array $orderBy)
    {
        $results = $resources;
        foreach ($orderBy as $property => $order) {
            $sortable = [];
            foreach ($results as $key => $object) {
                $sortable[$key] = $this->accessor->getValue($object, $property);
            }
            if (self::ORDER_ASCENDING === $order) {
                asort($sortable);
            }
            if (self::ORDER_DESCENDING === $order) {
                arsort($sortable);
            }
            $results = [];
            foreach ($sortable as $key => $propertyValue) {
                $results[$key] = $resources[$key];
            }
        }

        return $results;
    }
}
