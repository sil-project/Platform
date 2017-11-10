<?php

/*
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
use Psr\Log\LoggerAwareInterface;
use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\Logger;

class LabelableListener implements LoggerAwareInterface, EventSubscriber
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

    /**
     * define Labelable mapping at runtime.
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
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

        if (!$this->hasTrait($reflectionClass, 'Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Labelable')) {
            return;
        }

        $this->logger->debug('[LabelableListener] Entering LabelableListener for « loadClassMetadata » event');

        // setting default mapping configuration for Labelable

        // name
        $metadata->mapField([
            'fieldName' => 'label',
            'type'      => 'string',
            'nullable'  => true,
        ]);

        $this->logger->debug(
            '[LabelableListener] Added Labelable mapping metadata to Entity',
            ['class' => $metadata->getName()]
        );
    }
}
