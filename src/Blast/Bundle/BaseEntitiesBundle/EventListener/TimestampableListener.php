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

namespace Blast\Bundle\BaseEntitiesBundle\EventListener;

use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\Logger;
use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Psr\Log\LoggerAwareInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class TimestampableListener implements LoggerAwareInterface, EventSubscriber
{
    use ClassChecker, Logger;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'loadClassMetadata',
            'prePersist',
            'preUpdate',
        ];
    }

    /**
     * define Timestampable mapping at runtime.
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        $reflectionClass = $metadata->getReflectionClass();

        if (!$reflectionClass || !$this->hasTrait($reflectionClass, 'Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Timestampable')) {
            return;
        } // return if current entity doesn't use Timestampable trait

        // Check if parents already have the Timestampable trait
        foreach ($metadata->parentClasses as $parent) {
            if ($this->classAnalyzer->hasTrait($parent, 'Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Timestampable')) {
                return;
            }
        }

        $this->logger->debug(
            '[TimestampableListener] Entering TimestampableListener for « loadClassMetadata » event'
        );

        // setting default mapping configuration for Timestampable

        // createdAt
        $metadata->mapField([
            'fieldName' => 'createdAt',
            'type'      => 'datetime',
        ]);

        // updatedAt
        $metadata->mapField([
            'fieldName' => 'updatedAt',
            'type'      => 'datetime',
        ]);

        $this->logger->debug(
            '[TimestampableListener] Added Timestampable mapping metadata to Entity',
            ['class' => $metadata->getName()]
        );
    }

    /**
     * sets Timestampable dateTime (createdAt and updatedAt) information when persisting entity.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if (!$this->hasTrait($entity, 'Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Timestampable')) {
            return;
        }

        $this->logger->debug(
            '[TimestampableListener] Entering TimestampableListener for « prePersist » event'
        );

        $now = new DateTime('NOW');
        $entity->setCreatedAt($now);
        $entity->setUpdatedAt($now);
    }

    /**
     * sets Timestampable dateTime (updatedAt) information when updating entity.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if (!$this->hasTrait($entity, 'Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Timestampable')) {
            return;
        }

        $this->logger->debug(
            '[TimestampableListener] Entering TimestampableListener for « preUpdate » event'
        );

        $now = new DateTime('NOW');
        $entity->setUpdatedAt($now);
    }

    /**
     * setTokenStorage.
     *
     * @param TokenStorage $tokenStorage
     */
    public function setTokenStorage(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
}
