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
     * @var ClassMapInterface
     */
    private $classMap;

    /**
    * @var RoutingInterface
     */
    private $routing;

    /**
     * @var RoutingInterface
     */
    private $api;

    public static function createFromAliasAndParameters(string $alias, array $parameters)
    {
        if (false === strpos($alias, '.')) {
            throw new \InvalidArgumentException(sprintf('Invalid alias "%s" supplied, it should conform to the following format "<prefix>.<name>".', $alias));
        }

        $aliasParts = explode('.', $alias);
        $alias = array_pop($aliasParts);
        $prefix = implode($aliasParts);
        $classMap =  ClassMap::fromArray($parameters['classes']);
        $routing = Routing::fromArray($parameters['routing']);
        $api = Routing::fromArray($parameters['api'])
        return new static($prefix, $alias, $classMap);
    }

    /**
     * @param string $name
     * @param string $applicationName
     * @param array  $parameters
     */
    private function __construct(string $prefix, string $alias, ClassMapInterface $classMap , RoutingInterface $routing, RoutingInterface $api)
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

    public function hasRouting(){
      return $this->routing->isEnabled();
    }

    public function hasApi(){
      return $this->api->isEnabled();
    }
}
