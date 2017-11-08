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

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Psr\Log\LoggerAwareInterface;
use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\Logger;

class TreeableListener implements LoggerAwareInterface, EventSubscriber
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
        /** @var ClassMetadata $metadata */
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

        if (!$this->hasTrait($reflectionClass, 'Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Treeable')) {
            return;
        }

        $this->logger->debug(
            '[TreeableListener] Entering TreeableListener for « loadClassMetadata » event'
        );

        if (!$metadata->hasField('materializedPath')) {
            $metadata->mapField([
                'fieldName' => 'materializedPath',
                'type'      => 'string',
                'length'    => 2048,
            ]);
        }

        if (!$metadata->hasField('sortMaterializedPath')) {
            $metadata->mapField([
                'fieldName' => 'sortMaterializedPath',
                'type'      => 'string',
                'length'    => 2048,
            ]);
        }

        if (!$metadata->customRepositoryClassName) {
            $metadata->setCustomRepositoryClass('Blast\Bundle\BaseEntitiesBundle\Entity\Repository\TreeableRepository');
        }
    }
}
