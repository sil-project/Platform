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

namespace Blast\Bundle\BaseEntitiesBundle\EventListener;

use Psr\Log\LoggerAwareInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\EventArgs;
use Gedmo\Tree\TreeListener;
use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\Logger;

class NestedTreeableListener extends TreeListener implements LoggerAwareInterface, EventSubscriber
{
    use ClassChecker, Logger;

    public function loadClassMetadata(EventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();

        $reflectionClass = $metadata->getReflectionClass();

        // Don't process if cannot use ReflexionClass
        if (!$reflectionClass) {
            return;
        }

        // Don't process superMappedClass
        if ($metadata->isMappedSuperclass) {
            return;
        }

        if (!$this->hasTrait($reflectionClass, 'Blast\Bundle\BaseEntitiesBundle\Entity\Traits\NestedTreeable')) {
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

        if (!$metadata->hasField('treeLft')) {
            $metadata->mapField([
                'fieldName' => 'treeLft',
                'type'      => 'integer',
                'gedmo'     => ['treeLeft'],
            ]);
        }

        if (!$metadata->hasField('treeRgt')) {
            $metadata->mapField([
                'fieldName' => 'treeRgt',
                'type'      => 'integer',
                'gedmo'     => 'treeRight',
            ]);
        }

        if (!$metadata->hasField('treeLvl')) {
            $metadata->mapField([
                'fieldName' => 'treeLvl',
                'type'      => 'integer',
                'gedmo'     => 'treeLevel',
            ]);
        }

        if (!$metadata->hasAssociation('treeChildren')) {
            $metadata->mapOneToMany([
                'fieldName'    => 'treeChildren',
                'targetEntity' => $fqcn,
                'mappedBy'     => 'treeParent',
                'orderBy'      => ['treeLft' => 'ASC'],
                'cascade'      => ['persist', 'remove'],
            ]);
        }

        if (!$metadata->hasAssociation('treeRoot')) {
            $metadata->mapManyToOne([
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

        if (!$metadata->hasAssociation('treeParent')) {
            $metadata->mapManyToOne([
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

        if (!$metadata->customRepositoryClassName) {
            $metadata->setCustomRepositoryClass('Gedmo\Tree\Entity\Repository\NestedTreeRepository');
        }

        if (isset(self::$configurations[$this->name][$metadata->name]) && self::$configurations[$this->name][$metadata->name]) {
            $this->getStrategy($om, $metadata->name)->processMetadataLoad($om, $metadata);
        }
    }
}
