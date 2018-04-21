<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\ResourceBundle\Doctrine\ORM\Repository;

use Blast\Component\Resource\Repository\ResourceRepositoryInterface;
use Blast\Component\Resource\Model\ResourceInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use InvalidArgumentException;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class ResourceRepository extends EntityRepository implements ResourceRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function get($id): ResourceInterface
    {
        $resource = $this->find($id);
        if (null == $resource) {
            throw new InvalidArgumentException('Resource with the given id does not exist');
        }

        return $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function addAll(array $resources): void
    {
        foreach ($resources as $resource) {
            $this->_em->persist($resource);
        }
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function add(ResourceInterface $resource): void
    {
        $this->_em->persist($resource);
        $this->_em->flush($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function removeAll(array $resources): void
    {
        foreach ($resources as $resource) {
            $this->_em->remove($resource);
        }
        $this->_em->flush($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(ResourceInterface $resource): void
    {
        $this->_em->remove($resource);
        $this->_em->flush($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function update(ResourceInterface $resource): void
    {
        $this->_em->flush($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName(): string
    {
        return parent::getClassName();
    }

    /**
     * {@inheritdoc}
     */
    public function createPaginator(array $criteria = [], array $sorting = []): iterable
    {
        $queryBuilder = parent::createQueryBuilder('o');
        $this->applyCriteria($queryBuilder, $criteria);
        $this->applySorting($queryBuilder, $sorting);

        return new Pagerfanta(new DoctrineORMAdapter($queryBuilder, false, false));
    }

    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = []): void
    {
        foreach ($criteria as $property => $value) {
            $name = $this->getPropertyName($property);
            if (null === $value) {
                $queryBuilder->andWhere($queryBuilder->expr()->isNull($name));
            } elseif (is_array($value)) {
                $queryBuilder->andWhere($queryBuilder->expr()->in($name, $value));
            } elseif ('' !== $value) {
                $parameter = str_replace('.', '_', $property);
                $queryBuilder
                ->andWhere($queryBuilder->expr()->eq($name, ':' . $parameter))
                ->setParameter($parameter, $value)
            ;
            }
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array        $sorting
     */
    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = []): void
    {
        foreach ($sorting as $property => $order) {
            if (!in_array($property, array_merge($this->_class->getAssociationNames(), $this->_class->getFieldNames()), true)) {
                continue;
            }
            if (!empty($order)) {
                $queryBuilder->addOrderBy($this->getPropertyName($property), $order);
            }
        }
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getPropertyName(string $name): string
    {
        if (false === strpos($name, '.')) {
            return 'o' . '.' . $name;
        }

        return $name;
    }
}
