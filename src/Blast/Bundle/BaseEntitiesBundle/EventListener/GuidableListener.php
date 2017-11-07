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

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Id\UuidGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Blast\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Blast\BaseEntitiesBundle\EventListener\Traits\Logger;
use Psr\Log\LoggerAwareInterface;

class GuidableListener implements LoggerAwareInterface, EventSubscriber
{
    use ClassChecker, Logger;

    protected $fieldMappingConfiguration = [
        'id'         => true,
        'fieldName'  => 'id',
        'type'       => 'guid',
        'columnName' => 'id',
    ];

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'loadClassMetadata',
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        // Do not generate id mapping twice for entities that extend a MappedSuperclass
        if ($metadata->isMappedSuperclass) {
            return;
        }

        // Do not generate id mapping twice for entities that use the SINGLE_TABLE inheritance mapping strategy.
        if ($metadata->isInheritanceTypeSingleTable() && !$metadata->subClasses) {
            return;
        }

        // Check if parents already have the Guidable trait
        foreach ($metadata->parentClasses as $parent) {
            if ($this->classAnalyzer->hasTrait($parent, 'Blast\BaseEntitiesBundle\Entity\Traits\Guidable')) {
                return;
            }
        }

        $this->logger->debug('[GuidableListener] Entering GuidableListener for « loadClassMetadata » event', [$metadata->getReflectionClass()->getName()]);

        $reflectionClass = $metadata->getReflectionClass();

        // return if the current entity doesn't use Guidable trait
        if (!$reflectionClass || !$this->hasTrait($reflectionClass, 'Blast\BaseEntitiesBundle\Entity\Traits\Guidable')) {
            return;
        }

        // Don't apply twice the uuid mapping
        if ($metadata->idGenerator instanceof UuidGenerator) {
            return;
        }

        $metadata->mapField($this->fieldMappingConfiguration);
        $metadata->setIdGenerator(new UuidGenerator());

        $this->logger->debug(
            '[GuidableListener] Added Guidable mapping metadata to Entity',
            ['class' => $metadata->getName()]
        );
    }
}
