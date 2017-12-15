<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\Logger;
use Psr\Log\LoggerAwareInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

class NamingListener implements LoggerAwareInterface, EventSubscriber
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

        // Transform Librinfo entities only
        if (strpos($metadata->getName(), 'Librinfo\\') !== 0) {
            return;
        }

        $this->logger->debug('[NamingListener] Entering NamingListener for « loadClassMetadata » event');

        $namingStrategy = $eventArgs
                ->getEntityManager()
                ->getConfiguration()
                ->getNamingStrategy();

        // create a FQDN for the representing table
        if ($namingStrategy->classToTableName($metadata->getName()) == $metadata->table['name']) {
            $metadata->table['name'] = $this->buildTableName($metadata->name);
        }

        // create a FQDN for the ManyToMany induced tables
        foreach ($metadata->associationMappings as $field => $mapping) {
            if ($mapping['type'] == ClassMetadataInfo::MANY_TO_MANY && $mapping['isOwningSide']) {
                if ($namingStrategy->classToTableName($mapping['joinTable']['name']) == $mapping['joinTable']['name']) {
                    $rc = new \ReflectionClass($mapping['targetEntity']);
                    $fqdn = $mapping['sourceEntity'] . '__' . $rc->getShortName();
                    $metadata->associationMappings[$field]['joinTable']['name'] = $this->buildTableName($fqdn);
                }
            }
        }

        $this->logger->debug(
            '[NamingListener] Added table naming strategy to Entity',
            ['class' => $metadata->getName()]
        );
    }

    protected static function buildTableName($class)
    {
        $tableName = str_replace('\\', '/', $class);

        // MyVendor/MyBundlenameBundle/Model/MyEntity
        if (preg_match('%^([^/]+)/([^/]+)Bundle/Entity/(.+)$%', $tableName, $match)) {
            $vendor = $match[1];
            $bundle = $match[2];
            $entity = $match[3];
            $tableName = sprintf('%s_%s_%s', $vendor, $bundle, $entity);
        }

        // MyVendor/.../Model/MyEntity
        elseif (preg_match('%^([^/]+)/.+/Model/(.+)$%', $tableName, $match)) {
            $vendor = $match[1];
            $entity = str_replace('Interface', '', $match[2]);
            $tableName = sprintf('%s_%s', $vendor, $entity);
        }

        $tableName = strtolower($tableName);
        $tableName = str_replace('/', '_', $tableName);

        return $tableName;
    }
}
