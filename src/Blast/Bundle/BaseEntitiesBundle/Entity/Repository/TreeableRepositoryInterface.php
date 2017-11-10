<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Entity\Repository;

use Doctrine\ORM\QueryBuilder;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Tree\NodeInterface;

interface TreeableRepositoryInterface
{
    /**
     * Constructs a query builder to get all root nodes.
     *
     * @param string $rootAlias
     *
     * @return QueryBuilder
     */
    public function getRootNodesQB($rootAlias = 't');

    /**
     * Returns all root nodes.
     *
     * @api
     *
     * @param string $rootAlias
     *
     * @return array
     */
    public function getRootNodes($rootAlias = 't');

    /**
     * Returns a node hydrated with its children and parents.
     *
     * @api
     *
     * @param string $path
     * @param string $rootAlias
     *
     * @return NodeInterface a node
     */
    public function getTree($path = '', $rootAlias = 't');

    public function getTreeExceptNodeAndItsChildrenQB(NodeInterface $entity, $rootAlias = 't');

    /**
     * Extracts the root node and constructs a tree using flat resultset.
     *
     * @param iterable|array $results a flat resultset
     *
     * @return NodeInterface
     */
    public function buildTree($results);

    /**
     * Constructs a query builder to get a flat tree, starting from a given path.
     *
     * @param string $path
     * @param string $rootAlias
     *
     * @return QueryBuilder
     */
    public function getFlatTreeQB($path = '', $rootAlias = 't');

//    /**
//     * manipulates the flat tree query builder before executing it.
//     * Override this method to customize the tree query
//     *
//     * @param QueryBuilder $qb
//     */
//    function addFlatTreeConditions(QueryBuilder $qb);

    /**
     * Executes the flat tree query builder.
     *
     * @return array the flat resultset
     */
    public function getFlatTree($path, $rootAlias = 't');

    /**
     * getRootNodesTree.
     *
     * Return all trees without distinction
     * It builds the tree for each root nodes
     * (May be slow on huge trees)
     *
     * @return array
     */
    public function getRootNodesWithTree();
}
