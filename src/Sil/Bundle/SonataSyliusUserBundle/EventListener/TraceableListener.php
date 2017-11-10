<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\SonataSyliusUserBundle\EventListener;

use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Sil\Bundle\SonataSyliusUserBundle\Entity\Traits\Traceable;
use Monolog\Logger;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class TraceableListener implements LoggerAwareInterface, EventSubscriber
{
    use ClassChecker;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @var string
     */
    protected $userClass;

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
     * define Traceable mapping at runtime.
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        $reflectionClass = $metadata->getReflectionClass();

        if (!$reflectionClass || !$this->hasTrait($reflectionClass, Traceable::class)) {
            return;
        } // return if current entity doesn't use Traceable trait

        // Check if parents already have the Traceable trait
        foreach ($metadata->parentClasses as $parent) {
            if ($this->classAnalyzer->hasTrait($parent, Traceable::class)) {
                return;
            }
        }

        $this->logger->debug('[TraceableListener] Entering TraceableListener for « loadClassMetadata » event');
        $this->logger->debug('[TraceableListener] Using « ' . $this->userClass . ' » as User class');

        // setting default mapping configuration for Traceable

        // createdBy
        $metadata->mapManyToOne([
            'targetEntity' => $this->userClass,
            'fieldName'    => 'createdBy',
            'joinColumn'   => [
                'name'                 => 'createdBy_id',
                'referencedColumnName' => 'id',
                'onDelete'             => 'SET NULL',
                'nullable'             => true,
            ],
        ]);

        // updatedBy
        $metadata->mapManyToOne([
            'targetEntity' => $this->userClass,
            'fieldName'    => 'updatedBy',
            'joinColumn'   => [
                'name'                 => 'updatedBy_id',
                'referencedColumnName' => 'id',
                'onDelete'             => 'SET NULL',
                'nullable'             => true,
            ],
        ]);

        $this->logger->debug('[TraceableListener] Added Traceable mapping metadata to Entity', ['class' => $metadata->getName()]);
    }

    /**
     * sets Traceable dateTime and user information when persisting entity.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if (!$this->hasTrait($entity, Traceable::class)) {
            return;
        }

        $this->logger->debug('[TraceableListener] Entering TraceableListener for « prePersist » event');
        $user = $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;
        if (!is_a($user, $this->userClass)) {
            $user = null;
        }
        $now = new DateTime('NOW');

        $entity->setCreatedBy($user);
        $entity->setUpdatedBy($user);
        $entity->setCreatedAt($now);
        $entity->setUpdatedAt($now);
    }

    /**
     * sets Traceable dateTime and user information when updating entity.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if (!$this->hasTrait($entity, Traceable::class)) {
            return;
        }

        $this->logger->debug('[TraceableListener] Entering TraceableListener for « preUpdate » event');

        $user = $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;
        if (!is_a($user, $this->userClass)) {
            $user = null;
        }
        $now = new DateTime('NOW');

        $entity->setUpdatedBy($user);
        $entity->setUpdatedAt($now);
    }

    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
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

    /**
     * @param string $userClass
     */
    public function setUserClass($userClass)
    {
        $this->userClass = $userClass;
    }
}
