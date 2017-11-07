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

namespace Blast\UtilsBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\EventArgs;
use Blast\UtilsBundle\Entity\CustomFilter;

class LoadClassMetadataListener implements EventSubscriber
{
    /**
     * @var array
     */
    private $parameters = [];

    public function getSubscribedEvents()
    {
        return [
            'loadClassMetadata',
        ];
    }

    public function loadClassMetadata(EventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();
        $reflectionClass = $metadata->getReflectionClass();

        if (!$reflectionClass) {
            return;
        }

        $fqcn = $reflectionClass->getName();

        if ($fqcn !== CustomFilter::class) {
            return;
        }

        if ($this->currentClassHasNotToBeMappedInDoctrine($fqcn)) {
            $metadata->isMappedSuperclass = true;
        }
    }

    private function currentClassHasNotToBeMappedInDoctrine($className)
    {
        // Don't map class with doctrine if feature is not enabled
        if ($this->parameters['features']['customFilters']['enabled'] === false) {
            return true;
        }

        // Don't map class with doctrine if entity is not the default one
        if ($this->parameters['features']['customFilters']['class'] !== CustomFilter::class) {
            return true;
        }

        return false;
    }

    /**
     * @param array parameters
     *
     * @return self
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }
}
