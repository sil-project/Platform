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

namespace Blast\Bundle\DoctrinePgsqlBundle\Datagrid;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery as BaseProxyQuery;

class ProxyQuery extends BaseProxyQuery
{
    /**
     * query hints that will be added just before the query execution.
     *
     * @var array
     */
    protected $_hints = array();

    /**
     * {@inheritdoc}
     */
    public function execute(array $params = array(), $hydrationMode = null)
    {
        // always clone the original queryBuilder
        $queryBuilder = clone $this->queryBuilder;

        // todo : check how doctrine behave, potential SQL injection here ...
        if ($this->getSortBy()) {
            $sortBy = $this->getSortBy();
            if (strpos($sortBy, '.') === false) { // add the current alias
                $sortBy = $queryBuilder->getRootAlias() . '.' . $sortBy;
            }
            $queryBuilder->addOrderBy($sortBy, $this->getSortOrder());
        } else {
            $queryBuilder->resetDQLPart('orderBy');
        }

        // Use ILIKE instead of LIKE for Postgresql
        if ('pdo_pgsql' == $queryBuilder->getEntityManager()->getConnection()->getDriver()->getName()) {
            $this->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Blast\Bundle\DoctrinePgsqlBundle\DoctrineExtensions\BlastWalker');
        }

        $query = $this->getFixedQueryBuilder($queryBuilder)->getQuery();

        foreach ($this->_hints as $name => $value) {
            $query->setHint($name, $value);
        }

        return $query->execute($params, $hydrationMode);
    }

    /**
     * This method alters the query to return a clean set of object with a working
     * set of Object.
     * We (Libre Informatique) have altered it to replace LIKE by ILIKE for PostgreSQL.
     *
     * @param QueryBuilder $queryBuilder
     *
     * @return QueryBuilder
     */
    protected function getFixedQueryBuilder(QueryBuilder $queryBuilder)
    {
        $queryBuilderId = clone $queryBuilder;
        $rootAlias = current($queryBuilderId->getRootAliases());

        // step 1 : retrieve the targeted class
        $from = $queryBuilderId->getDQLPart('from');
        $class = $from[0]->getFrom();
        $metadata = $queryBuilderId->getEntityManager()->getMetadataFactory()->getMetadataFor($class);

        // step 2 : retrieve identifier columns
        $idNames = $metadata->getIdentifierFieldNames();

        // step 3 : retrieve the different subjects ids
        $selects = array();
        $idxSelect = '';
        foreach ($idNames as $idName) {
            $select = sprintf('%s.%s', $rootAlias, $idName);
            // Put the ID select on this array to use it on results QB
            $selects[$idName] = $select;
            // Use IDENTITY if id is a relation too. See: http://doctrine-orm.readthedocs.org/en/latest/reference/dql-doctrine-query-language.html
            // Should work only with doctrine/orm: ~2.2
            $idSelect = $select;
            if ($metadata->hasAssociation($idName)) {
                $idSelect = sprintf('IDENTITY(%s) as %s', $idSelect, $idName);
            }
            $idxSelect .= ($idxSelect !== '' ? ', ' : '') . $idSelect;
        }

        foreach ($queryBuilderId->getDqlParts()['orderBy'] as $sort) {
            $field = chop($sort->getParts()[0], 'ASC');
            $field = chop($field, 'DESC');
            $idxSelect .= ', (' . $field . ')';
        }

        $queryBuilderId->resetDQLPart('select');
        $queryBuilderId->add('select', 'DISTINCT ' . $idxSelect);

        // for SELECT DISTINCT, ORDER BY expressions must appear in idxSelect list
        /* Consider
            SELECT DISTINCT x FROM tab ORDER BY y;
        For any particular x-value in the table there might be many different y
        values.  Which one will you use to sort that x-value in the output?
        */
        // todo : check how doctrine behave, potential SQL injection here ...
        if ($this->getSortBy()) {
            $sortBy = $this->getSortBy();
            if (strpos($sortBy, '.') === false) { // add the current alias
                $sortBy = $rootAlias . '.' . $sortBy;
            }
            $sortBy .= ' AS __order_by';
            $queryBuilderId->addSelect($sortBy);
        }

        // Use ILIKE instead of LIKE for Postgresql
        if ('pdo_pgsql' == $queryBuilderId->getEntityManager()->getConnection()->getDriver()->getName()) {
            $this->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Blast\Bundle\DoctrinePgsqlBundle\DoctrineExtensions\BlastWalker');
        }

        $query = $queryBuilderId->getQuery();
        foreach ($this->_hints as $name => $value) {
            $query->setHint($name, $value);
        }

        $results = $query->execute(array(), Query::HYDRATE_ARRAY);
        $platform = $queryBuilderId->getEntityManager()->getConnection()->getDatabasePlatform();
        $idxMatrix = array();
        foreach ($results as $id) {
            foreach ($idNames as $idName) {
                // Convert ids to database value in case of custom type, if provided.
                $fieldType = $metadata->getTypeOfField($idName);
                $idxMatrix[$idName][] = $fieldType && Type::hasType($fieldType)
                    ? Type::getType($fieldType)->convertToDatabaseValue($id[$idName], $platform)
                    : $id[$idName];
            }
        }

        // step 4 : alter the query to match the targeted ids
        foreach ($idxMatrix as $idName => $idx) {
            if (count($idx) > 0) {
                $idxParamName = sprintf('%s_idx', $idName);
                $idxParamName = preg_replace('/[^\w]+/', '_', $idxParamName);
                $queryBuilder->andWhere(sprintf('%s IN (:%s)', $selects[$idName], $idxParamName));
                $queryBuilder->setParameter($idxParamName, $idx);
                $queryBuilder->setMaxResults(null);
                $queryBuilder->setFirstResult(null);
            }
        }

        return $queryBuilder;
    }

    /**
     * Sets a query hint that will be used just before the query execution.
     *
     * @param string $name  the name of the hint
     * @param mixed  $value the value of the hint
     *
     * @return static this instance
     */
    public function setHint($name, $value)
    {
        $this->_hints[$name] = $value;

        return $this;
    }
}
