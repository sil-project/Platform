<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\EventListener;

use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\Logger;
use Sil\Bundle\EcommerceBundle\Entity\Payment;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Psr\Log\LoggerAwareInterface;

class PaymentMappingListener implements LoggerAwareInterface, EventSubscriber
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
     * define Addressable mapping at runtime.
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        /** @var \ReflectionClass $reflectionClass */
        $reflectionClass = $metadata->getReflectionClass();

        if ($reflectionClass->getName() !== Payment::class) {
            return;
        }

        $this->logger->debug(
            '[PaymentListener] Entering PaymentListener for « loadClassMetadata » event',
            [$metadata->getReflectionClass()->getName()]
        );

        unset($metadata->fieldMappings['details']);
        unset($metadata->fieldNames['details']);
        unset($metadata->columnNames['details']);

        $metadata->mapField([
            'fieldName' => 'details',
            'type'      => 'array',
        ]);
    }
}
