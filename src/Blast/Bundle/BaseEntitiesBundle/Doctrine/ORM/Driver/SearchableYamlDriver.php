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

namespace Blast\BaseEntitiesBundle\Doctrine\ORM\Driver;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;

/**
 * Adds searchable functionnality to the Doctrine Yaml metadata driver.
 *
 * @todo : This is not used (yet)
 */
class SearchableYamlDriver extends SimplifiedYamlDriver
{
    public function loadMetadataForClass($className, ClassMetadata $metadata)
    {
        parent::loadMetadataForClass($className, $metadata);

        // TODO : force use of SearchableClassMetadata instead of ClassMetadata
    }
}
