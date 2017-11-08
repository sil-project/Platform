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

use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Psr\Log\LoggerAwareInterface;
use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\Logger;
use Blast\Bundle\BaseEntitiesBundle\Entity\Repository\SortableRepository;

class SortableListener implements LoggerAwareInterface, EventSubscriber
{
    use ClassChecker,
        Logger;

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
        ];
    }

    /**
     * define Sortable mapping at runtime.
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

        if (!$this->hasTrait($reflectionClass, 'Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Sortable')) {
            return;
        }

        $this->logger->debug('[SortableListener] Entering SortableListener for « loadClassMetadata » event');

        // setting default mapping configuration for Sortable
        // sortRank
        $metadata->mapField([
            'fieldName' => 'sortRank',
            'type'      => 'float',
            'nullable'  => false,
            'default'   => 65536,
        ]);

        // add index on sort_rank column
        if (!isset($metadata->table['indexes'])) {
            $metadata->table['indexes'] = [];
        }

        $metadata->table['indexes']['sort_rank'] = ['columns' => ['sort_rank']];

        $this->logger->debug(
            '[SortableListener] Added Sortable mapping metadata to Entity', ['class' => $metadata->getName()]
        );
    }

    /**
     * Compute sortRank for entities that are created.
     *
     * @param EventArgs $args
     */
    public function prePersist(EventArgs $args)
    {
        $em = $args->getEntityManager();
        $object = $args->getObject();
        $class = get_class($object);
        $meta = $em->getClassMetadata($class);

        $reflectionClass = $meta->getReflectionClass();

        if (!$reflectionClass || !$this->hasTrait($reflectionClass, 'Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Sortable')) {
            return;
        } // return if current entity doesn't use Sortable trait

        if ($object->getSortRank()) {
            return;
        }

        $maxPos = $this->getMaxPosition($em, $meta);
        $maxPos = $maxPos ? $maxPos + 1000 : 65536;

        $object->setSortRank($maxPos);
        $this->maxPositions[$class] = $maxPos;
    }

    private function getMaxPosition($em, $meta)
    {
        $class = $meta->name;
        if (isset($this->maxPositions[$class])) {
            return $this->maxPositions[$class];
        }

        $repo = new SortableRepository($em, $meta);

        return $repo->getMaxPosition();
    }
}
