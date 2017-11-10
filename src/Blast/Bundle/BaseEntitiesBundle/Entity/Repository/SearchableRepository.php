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

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityRepository;

class SearchableRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        $this->_entityName = $class->name;
        $this->_em = $em;
        $this->_class = $class;
    }

    public function getSearchIndexClass()
    {
        return $this->getClassName() . 'SearchIndex';
    }

    public function getSearchIndexTable()
    {
        return $this->getEntityManager()->getClassMetadata($this->getSearchIndexClass())->getTableName();
    }

    public function batchUpdate()
    {
        throw new \Exception('Deprecated, use service « blast_base_entities.search_handler » instead.');
    }

    public function indexSearch($searchText, $maxResults)
    {
        throw new \Exception('Deprecated, use service « blast_base_entities.search_handler » instead.');
    }

    public function truncateTable()
    {
        throw new \Exception('Deprecated, use service « blast_base_entities.search_handler » instead.');
    }
}
