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
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use InvalidArgumentException;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class NestedTreeResourceRepository extends NestedTreeRepository implements ResourceRepositoryInterface
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
        $this->_em->flush($resource);
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
}
