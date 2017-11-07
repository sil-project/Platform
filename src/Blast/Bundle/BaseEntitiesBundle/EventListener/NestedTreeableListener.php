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

namespace Blast\BaseEntitiesBundle\EventListener;

use Psr\Log\LoggerAwareInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\EventArgs;
use Gedmo\Tree\TreeListener;
use Blast\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Blast\BaseEntitiesBundle\EventListener\Traits\Logger;

class NestedTreeableListener extends TreeListener implements LoggerAwareInterface, EventSubscriber
{
    use ClassChecker, Logger;

    public function loadClassMetadata(EventArgs $eventArgs)
    {
        $meta = $eventArgs->getClassMetadata();
        $reflectionClass = $meta->getReflectionClass();

        if (!$reflectionClass || !$this->hasTrait($reflectionClass, 'Blast\BaseEntitiesBundle\Entity\Traits\NestedTreeable')) {
            return;
        } // return if current entity doesn't use NestedTreeable trait

        $this->logger->debug(
            '[NestedTreeableListener] Entering NestedTreeableListener for « loadClassMetadata » event'
        );

        $ea = $this->getEventAdapter($eventArgs);
        $om = $ea->getObjectManager();
        $fqcn = $reflectionClass->getName();

        self::$configurations['Tree'] = [
            $fqcn => [
                'strategy'         => 'nested',
                'activate_locking' => false,
                'locking_timeout'  => 3,
                'left'             => 'treeLft',
                'right'            => 'treeRgt',
                'level'            => 'treeLvl',
                'root'             => 'treeRoot',
                'parent'           => 'treeParent',
                'useObjectClass'   => $fqcn,
            ],
        ];

        if (!$meta->hasField('treeLft')) {
            $meta->mapField([
                'fieldName' => 'treeLft',
                'type'      => 'integer',
                'gedmo'     => ['treeLeft'],
            ]);
        }

        if (!$meta->hasField('treeRgt')) {
            $meta->mapField([
                'fieldName' => 'treeRgt',
                'type'      => 'integer',
                'gedmo'     => 'treeRight',
            ]);
        }

        if (!$meta->hasField('treeLvl')) {
            $meta->mapField([
                'fieldName' => 'treeLvl',
                'type'      => 'integer',
                'gedmo'     => 'treeLevel',
            ]);
        }

        if (!$meta->hasAssociation('treeChildren')) {
            $meta->mapOneToMany([
                'fieldName'    => 'treeChildren',
                'targetEntity' => $fqcn,
                'mappedBy'     => 'treeParent',
                'orderBy'      => ['treeLft' => 'ASC'],
                'cascade'      => ['persist', 'remove'],
            ]);
        }

        if (!$meta->hasAssociation('treeRoot')) {
            $meta->mapManyToOne([
                'fieldName'    => 'treeRoot',
                'targetEntity' => $fqcn,
                'join_column'  => [
                    'name'                 => 'tree_root',
                    'referencedColumnName' => 'id',
                    ],
                'onDelete' => 'CASCADE',
                'gedmo'    => 'treeRoot',
            ]);
        }

        if (!$meta->hasAssociation('treeParent')) {
            $meta->mapManyToOne([
                'fieldName'    => 'treeParent',
                'targetEntity' => $fqcn,
                'inversedBy'   => 'treeChildren',
                'join_column'  => [
                    'name'                 => 'tree_parent_id',
                    'referencedColumnName' => 'id',
                    'onDelete'             => 'CASCADE',
                    ],
                'gedmo' => 'treeParent',
            ]);
        }

        if (!$meta->customRepositoryClassName) {
            $meta->setCustomRepositoryClass('Gedmo\Tree\Entity\Repository\NestedTreeRepository');
        }

        if (isset(self::$configurations[$this->name][$meta->name]) && self::$configurations[$this->name][$meta->name]) {
            $this->getStrategy($om, $meta->name)->processMetadataLoad($om, $meta);
        }
    }
}
