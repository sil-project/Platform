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

namespace Sil\Bundle\EcommerceBundle\Dashboard\Stats;

use Doctrine\DBAL\Connection;
use Doctrine\Bundle\DoctrineBundle\Registry;

abstract class AbstractStats
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var Registry
     */
    protected $doctrine;

    abstract public function getData(array $parameters = []): array;

    /**
     * @param Connection $connection
     */
    public function setConnection(Connection $connection): void
    {
        $this->connection = $connection;
    }

    /**
     * @param Registry $doctrine
     */
    public function setDoctrine(Registry $doctrine): void
    {
        $this->doctrine = $doctrine;
    }
}
