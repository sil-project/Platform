<?php

/*
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
    private $prefix;
    /**
     * @var string
     */
    private $alias;

    /**
     * @var ClassMap
     */
    private $classMap;

    public static function createFromAliasAndParameters(string $alias, array $parameters)
    {
        if (false === strpos($alias, '.')) {
            throw new \InvalidArgumentException(sprintf('Invalid alias "%s" supplied, it should conform to the following format "<prefix>.<name>".', $alias));
        }

        $aliasParts = explode('.', $alias);
        $alias = array_pop($aliasParts);
        $prefix = implode($aliasParts);

        return new static($prefix, $alias,  ClassMap::fromArray($parameters['classes']));
    }

    /**
     * @param string $name
     * @param string $applicationName
     * @param array  $parameters
     */
    private function __construct(string $prefix, string $alias, ClassMapInterface $classMap)
    {
        $this->prefix = $prefix;
        $this->alias = $alias;
        $this->classMap = $classMap;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getFullyQualifiedName(): string
    {
        return $this->prefix . '.' . $this->alias;
    }

    public function getClassMap(): ClassMapInterface
    {
        return $this->classMap;
    }
}
