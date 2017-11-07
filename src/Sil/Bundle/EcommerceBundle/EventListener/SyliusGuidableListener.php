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

namespace Sil\Bundle\EcommerceBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Id\UuidGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\Logger;
use Psr\Log\LoggerAwareInterface;

/**
 * Service that forces GUID doctrine mapping for Sylius entities primary keys.
 */
class SyliusGuidableListener implements LoggerAwareInterface, EventSubscriber
{
    use ClassChecker, Logger;

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
        /**
         * @var ClassMetadata
         */
        $metadata = $eventArgs->getClassMetadata();

        // Transform Sylius entities only (check class ancestors too)
        $isSylius = false;
        if (strpos($metadata->getName(), 'Sylius\\') === 0) {
            $isSylius = true;
        } else {
            foreach ($this->getAncestors($metadata->name) as $parent) {
                if (strpos($parent, 'Sylius\\') === 0) {
                    $isSylius = true;
                    break;
                }
            }
        }

        if (!$isSylius) {
            return;
        }

        // Skip if there is no id field
        if (!$metadata->hasField('id')) {
            return;
        }

        // Do not generate id mapping twice for entities that extend a MappedSuperclass
        if ($metadata->isMappedSuperclass) {
            return;
        }

        // Do not generate id mapping twice for entities that use the SINGLE_TABLE inheritance mapping strategy.
        if ($metadata->isInheritanceTypeSingleTable() && !$metadata->subClasses) {
            return;
        }

        $this->logger->debug('[SyliusGuidableListener] Entering SyliusGuidableListener for « loadClassMetadata » event');

        // we CANNOT change field type (from int to guid) with $metadata->setAttributeOverride(...)
        // so we have to unmap and remap (kind of dirty)
        unset($metadata->fieldMappings['id']);
        unset($metadata->fieldNames['id']);
        unset($metadata->columnNames['id']);
        $metadata->mapField(
            [
            'id'         => true,
            'fieldName'  => 'id',
            'type'       => 'guid',
            'columnName' => 'id',
            ]
        );
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_UUID);
        $metadata->setIdGenerator(new UuidGenerator());

        $this->logger->debug(
            '[SyliusGuidableListener] Added Guid mapping metadata to Entity ' . $metadata->getName(),
            ['class' => $metadata->getName()]
        );
    }
}
