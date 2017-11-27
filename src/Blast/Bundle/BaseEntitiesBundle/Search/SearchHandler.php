<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Search;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class SearchHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $_em;

    /**
     * @var string
     */
    private $_entityName;

    /**
     * @var ClassMetadata
     */
    private $_class;

    /**
     * @var array
     */
    private $searchIndexes;

    public function __construct(EntityManagerInterface $em, array $searchIndexes)
    {
        $this->_em = $em;
        $this->searchIndexes = $searchIndexes;
    }

    public function handleEntity(ClassMetadata $class)
    {
        $this->_entityName = $class->name;
        $this->_class = $class;
    }

    /**
     * Find the entities that have the appropriate keywords in their searchIndex.
     *
     * @param string $searchText
     * @param int    $maxResults
     *
     * @return Collection found entities
     */
    public function indexSearch($entityClass, $searchText, $maxResults = -1, $queryBuilder = null)
    {
        // split the phrase into words
        $words = SearchAnalyser::analyse($searchText);
        if (!$words) {
            return [];
        }

        if ($queryBuilder === null) {
            $queryBuilder = $this->getSearchQueryBuilder();
        }

        $this->alterSearchQueryBuilder($queryBuilder, $words, $maxResults);

        $results = $queryBuilder->getQuery()->execute();

        return $results;
    }

    public function getSearchQueryBuilder()
    {
        return $this->_em->createQueryBuilder();
    }

    public function alterSearchQueryBuilder(&$queryBuilder, $searchText, $maxResults = -1)
    {
        if (!is_array($searchText)) {
            $searchText = SearchAnalyser::analyse($searchText);
        }

        $parameters = [];
        $orModule = $queryBuilder->expr()->orx();
        foreach ($this->getIndexesForClass($this->_class) as $field) {
            foreach ($searchText as $k => $word) {
                $orModule->add($queryBuilder->expr()->like('o.' . $field, ':word_' . $k));
                $parameters["word_$k"] = '%' . $word . '%';
            }
        }

        $queryBuilder->andWhere($orModule);
        $queryBuilder->setParameters($parameters);

        return $queryBuilder;
    }

    public function getIndexesForClass(ClassMetadata $entityClass): array
    {
        if (array_key_exists($entityClass->name, $this->searchIndexes)) {
            return $this->searchIndexes[$entityClass->name]['fields'];
        }

        return [];
    }
}
