<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\SonataSyliusUserBundle\EventListener;

use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Sil\Bundle\SonataSyliusUserBundle\Entity\Traits\Ownable;
use Monolog\Logger;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class OwnableListener implements LoggerAwareInterface, EventSubscriber
{
    use ClassChecker;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var string
     */
    private $userClass;

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
     * define Ownable mapping at runtime.
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        $reflectionClass = $metadata->getReflectionClass();

        if (!$reflectionClass || !$this->hasTrait($reflectionClass, Ownable::class)) {
            return;
        } // return if current entity doesn't use Ownable trait

        // Check if parents already have the Ownable trait
        foreach ($metadata->parentClasses as $parent) {
            if ($this->classAnalyzer->hasTrait($parent, Ownable::class)) {
                return;
            }
        }

        $this->logger->debug('[OwnableListener] Entering OwnableListener for « loadClassMetadata » event');

        // setting default mapping configuration for Ownable

        // owner mapping
        $metadata->mapManyToOne([
            'targetEntity' => $this->userClass,
            'fieldName'    => 'owner',
            'joinColumn'   => [
                'name'                 => 'owner_id',
                'referencedColumnName' => 'id',
                'onDelete'             => 'SET NULL',
                'nullable'             => true,
            ],
        ]);

        $this->logger->debug('[OwnableListener] Added Ownable mapping metadata to Entity', ['class' => $metadata->getName()]);
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
     * @param string $userClass
     */
    public function setUserClass($userClass)
    {
        $this->userClass = $userClass;
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
