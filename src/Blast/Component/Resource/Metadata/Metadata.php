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
    private $prefix;
    /**
     * @var string
     */
    private $alias;

    /**
     * @var ClassMap
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
        $classMap = ClassMap::fromArray($parameters['classes']);
        $routing = Routing::createFrom($prefix, $alias, $parameters['routing']);
        $api = Routing::createFrom($prefix, $alias, $parameters['api'] ?? []);

        return new static($prefix, $alias,  $classMap, $routing, $api);
    }

    /**
     * @param string $name
     * @param string $applicationName
     * @param array  $parameters
     */
    private function __construct(string $prefix, string $alias, ClassMapInterface $classMap, RoutingInterface $routing, RoutingInterface $api)
    {
        $this->prefix = $prefix;
        $this->alias = $alias;
        $this->classMap = $classMap;
        $this->routing = $routing;
        $this->api = $api;
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

    public function hasRouting()
    {
        return $this->routing->isEnabled();
    }

    public function getRouting()
    {
        return $this->routing;
    }

    public function hasApi()
    {
        return $this->api->isEnabled();
    }

    public function getApi()
    {
        return $this->api;
    }

    public function getServiceId($type): string
    {
        return sprintf('%s.' . $type . '.%s', $this->getPrefix(), $this->getAlias());
    }

    public function getParameterId($type): string
    {
        return sprintf('%s.class', $this->getServiceId($type));
    }
}
