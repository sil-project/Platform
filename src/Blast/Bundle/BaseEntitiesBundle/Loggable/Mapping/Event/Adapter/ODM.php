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

namespace Blast\Bundle\BaseEntitiesBundle\Loggable\Mapping\Event\Adapter;

use Gedmo\Mapping\Event\Adapter\ODM as BaseAdapterODM;
use Gedmo\Loggable\Mapping\Event\LoggableAdapter;

/**
 * Doctrine event adapter for ODM adapted
 * for Loggable behavior.
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
final class ODM extends BaseAdapterODM implements LoggableAdapter
{
    /**
     * {@inheritdoc}
     */
    public function getDefaultLogEntryClass()
    {
        return 'Gedmo\\Loggable\\Document\\LogEntry';
    }

    /**
     * {@inheritdoc}
     */
    public function isPostInsertGenerator($meta)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewVersion($meta, $object)
    {
        $dm = $this->getObjectManager();
        $objectMeta = $dm->getClassMetadata(get_class($object));
        $identifierField = $this->getSingleIdentifierFieldName($objectMeta);
        $objectId = $objectMeta->getReflectionProperty($identifierField)->getValue($object);

        $qb = $dm->createQueryBuilder($meta->name);
        $qb->select('version');
        $qb->field('objectId')->equals($objectId);
        $qb->field('objectClass')->equals($objectMeta->name);
        $qb->sort('version', 'DESC');
        $qb->limit(1);
        $q = $qb->getQuery();
        $q->setHydrate(false);

        $result = $q->getSingleResult();
        if ($result) {
            $result = $result['version'] + 1;
        }

        return $result;
    }
}
