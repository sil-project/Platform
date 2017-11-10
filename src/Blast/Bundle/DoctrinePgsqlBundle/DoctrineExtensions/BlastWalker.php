<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\DoctrinePgsqlBundle\DoctrineExtensions;

use Doctrine\ORM\Query\SqlWalker;

class BlastWalker extends SqlWalker
{
    /**
     * Walks down a SelectClause AST node, thereby generating the appropriate SQL.
     *
     * @param $selectClause
     *
     * @return string the SQL
     */
    public function walkSelectClause($selectClause)
    {
        $sql = parent::walkSelectClause($selectClause);

        if ($this->getQuery()->getHint('blastWalker.noIlike') === false) {
            $sql = str_replace(' LIKE ', ' ILIKE ', $sql);
        }

        return $sql;
    }

    /**
     * Walks down a WhereClause AST node, thereby generating the appropriate SQL.
     *
     * @param $whereClause
     *
     * @return string the SQL
     */
    public function walkWhereClause($whereClause)
    {
        $sql = parent::walkWhereClause($whereClause);

        if ($this->getQuery()->getHint('blastWalker.noIlike') === false) {
            $sql = str_replace(' LIKE ', ' ILIKE ', $sql);
        }

        return $sql;
    }

    /**
     * {@inheritdoc}
     */
    public function walkLikeExpression($likeExpr)
    {
        $sql = parent::walkLikeExpression($likeExpr);

        if ($this->getQuery()->getHint('blastWalker.noIlike') === false) {
            $sql = str_replace(' LIKE ', ' ILIKE ', $sql);
        }

        return $sql;
    }
}
