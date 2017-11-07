<?php

/*
 * This file is part of the Blast Project package.
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

    public function getSearchIndexClass()
    {
        return $this->_entityName . 'SearchIndex';
    }

    public function getSearchIndexTable()
    {
        return $this->_em->getClassMetadata($this->getSearchIndexClass())->getTableName();
    }

    /**
     * Does a batch update of the whole search index table.
     */
    public function batchUpdate()
    {
        if ($this->_entityName === null) {
            throw new \Exception('Please call « handleEntity(ClassMetadata $class) » before using this handler');
        }
        if (!$this->truncateTable()) {
            throw new \Exception('Could not truncate table ' . $this->getSearchIndexTable());
        }
        $em = $this->_em;
        $indexClass = $this->getSearchIndexClass();

        $batchSize = 100;
        $offset = 0;
        $query = $this->_em->createQueryBuilder()
            ->select('o')
            ->from($this->_entityName, 'o')
            ->setMaxResults($batchSize);

        do {
            $query->setFirstResult($offset);
            $entities = $query->getQuery()->execute();
            foreach ($entities as $entity) {
                $fields = $this->searchIndexes[$this->_class->getName()]['fields'];
                foreach ($fields as $field) {
                    $keywords = $entity->analyseField($field);
                    foreach ($keywords as $keyword) {
                        $index = new $indexClass();
                        $index->setObject($entity);
                        $index->setField($field);
                        $index->setKeyword($keyword);
                        $em->persist($index);
                    }
                }
            }

            $em->flush();
            $offset += $batchSize;
        } while (count($entities) > 0);
    }

    /**
     * Find the entities that have the appropriate keywords in their searchIndex.
     *
     * @param string $searchText
     * @param int    $maxResults
     *
     * @return Collection found entities
     */
    public function indexSearch($searchText, $maxResults)
    {
        // split the phrase into words
        $words = SearchAnalyser::analyse($searchText);
        if (!$words) {
            return [];
        }

        $query = $this->_em->createQueryBuilder()
            ->select('o')
            ->from($this->_entityName, 'o')
            ->setMaxResults($maxResults);

        $parameters = [];
        foreach ($words as $k => $word) {
            $subquery = $this->_em->createQueryBuilder()
                ->select("i$k.keyword")
                ->from($this->getSearchIndexClass(), "i$k")
                ->where("i$k.object = o")
                ->andWhere("i$k.keyword ILIKE :search$k");
            $query->andWhere($query->expr()->exists($subquery->getDql()));
            $parameters["search$k"] = '%' . $word . '%';
        }
        $query->setParameters($parameters);

        $results = $query->getQuery()->execute();

        return $results;
    }

    /**
     * Truncates the search index table.
     *
     * @return bool true if success
     */
    public function truncateTable()
    {
        $connection = $this->_em->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            $q = $dbPlatform->getTruncateTableSql($this->getSearchIndexTable());
            $connection->executeUpdate($q);
            $connection->commit();

            return true;
        } catch (\Exception $e) {
            $connection->rollback();

            return false;
        }
    }
}
