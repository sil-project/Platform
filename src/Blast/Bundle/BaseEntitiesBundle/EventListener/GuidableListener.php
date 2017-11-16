<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Id\UuidGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\Logger;
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

        $reflectionClass = $metadata->getReflectionClass();

        // Don't process if cannot use ReflexionClass
        if (!$reflectionClass) {
            return;
        }

        // Do not generate id mapping twice for entities that use the SINGLE_TABLE inheritance mapping strategy.
        if ($metadata->isInheritanceTypeSingleTable() && !$metadata->subClasses) {
            return;
        }

        // return if the current entity doesn't use Guidable trait
        if (!$this->hasTrait($reflectionClass, 'Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable')) {
            return;
        }

        // Don't apply twice the uuid mapping
        if ($metadata->idGenerator instanceof UuidGenerator) {
            return;
        }

        $this->logger->debug('[GuidableListener] Entering GuidableListener for « loadClassMetadata » event', [$metadata->getReflectionClass()->getName()]);

        if (count($metadata->getIdentifier()) !== 0) {
            $metadata->setIdGenerator(null);
            $metadata->setAttributeOverride($this->fieldMappingConfiguration['fieldName'], $this->fieldMappingConfiguration);
        } else {
            $metadata->mapField($this->fieldMappingConfiguration);
        }

        $metadata->setIdGenerator(new UuidGenerator());

        $this->logger->debug(
            '[GuidableListener] Added Guidable mapping metadata to Entity',
            ['class' => $metadata->getName()]
        );
    }
}
