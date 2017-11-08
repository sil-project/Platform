<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Loggable\Mapping\Event\Adapter;

use Gedmo\Mapping\Event\Adapter\ORM as BaseAdapterORM;
use Gedmo\Loggable\Mapping\Event\LoggableAdapter;

/**
 * Doctrine event adapter for ORM adapted
 * for Loggable behavior.
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
final class ORM extends BaseAdapterORM implements LoggableAdapter
{
    /**
     * {@inheritdoc}
     */
    public function getDefaultLogEntryClass()
    {
        return 'Gedmo\\Loggable\\Entity\\LogEntry';
    }

    /**
     * {@inheritdoc}
     */
    public function isPostInsertGenerator($meta)
    {
        return $meta->idGenerator->isPostInsertGenerator();
    }

    /**
     * {@inheritdoc}
     */
    public function getNewVersion($meta, $object)
    {
        $em = $this->getObjectManager();
        $objectMeta = $em->getClassMetadata(get_class($object));
        $identifierField = $this->getSingleIdentifierFieldName($objectMeta);
        $objectId = $objectMeta->getReflectionProperty($identifierField)->getValue($object);

        $dql = "SELECT MAX(log.version) FROM {$meta->name} log";
        $dql .= ' WHERE log.objectId = :objectId';
        $dql .= ' AND log.objectClass = :objectClass';

        $q = $em->createQuery($dql);
        $q->setParameters(array(
            'objectId'    => $objectId,
            'objectClass' => $objectMeta->name,
        ));

        return $q->getSingleScalarResult() + 1;
    }
}
