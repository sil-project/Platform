<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\Resource\Metadata;

/**
 * Description of Metadata.
 *
 * @author glenn
 */
class Metadata implements MetadataInterface
{
    /**
     * @var string
     */
    private $alias;

    /**
     * @var ClassMap
     */
    private $classMap;

    /**
     * @param string $name
     * @param string $applicationName
     * @param array  $parameters
     */
    public function __construct(string $alias, ClassMapInterface $classMap)
    {
        $this->alias = $alias;
        $this->classMap = $classMap;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getClassMap(): ClassMapInterface
    {
        return $this->classMap;
    }
}
