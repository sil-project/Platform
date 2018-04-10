<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\ResourceBundle\Routing;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Blast\Component\Resource\Metadata\MetadataRegistryInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouteLoader implements LoaderInterface
{
    /**
     * @var MetadataRegistryInterface
     */
    private $metadataRegistry;

    /**
     * @param MetadataRegistryInterface $metadataRegistry
     */
    public function __construct(MetadataRegistryInterface $metadataRegistry)
    {
        $this->metadataRegistry = $metadataRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null): RouteCollection
    {
        $metadata = $this->metadataRegistry->get($resource);
        $routes = new RouteCollection();

        if ($metadata->hasRouting()) {
            $this->loadMetadataRoutes($metadata, $routes);
        }
        if ($metadata->hasApi()) {
            $this->loadMetadataApi($metadata, $routes);
        }

        return $routes;
    }

    protected function loadMetadataRoutes($metadata, $routes)
    {
        $actions = $metadata->getRouting()->getActions();

        foreach ($actions as $action) {
            $actionName = $action->getAction();
            $methods = $action->getMethods();
            $path = $action->getPath();
            $reqs = $action->getRequirements();
            $defaults = [
              '_controller' => $action->getController() ?? $metadata->getServiceId('controller') . sprintf(':%sAction', $actionName),
            ];
            $route = $this->createRoute($path, $defaults, $reqs, [], '', [], $methods);
            $routes->add($action->getName(), $route);
        }
    }

    public function createRoute(
        string $path,
        array $defaults = [],
        array $requirements = [],
        array $options = [],
        string $host = '',
        array $schemes = [],
        array $methods = [],
        string $condition = '')
    {
        return new Route($path, $defaults, $requirements, $options, $host, $schemes, $methods, $condition);
    }

    protected function loadMetadataApi($metadata, $routes)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null): bool
    {
        return 'blast.resource' === $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver(): void
    {
        // Intentionally left blank.
    }

    /**
     * {@inheritdoc}
     */
    public function setResolver(LoaderResolverInterface $resolver): void
    {
        // Intentionally left blank.
    }
}
