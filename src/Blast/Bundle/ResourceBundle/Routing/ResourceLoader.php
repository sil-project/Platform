<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\ResourceBundle\Routing;

use Symfony\Component\Config\Loader\LoaderInterface;
use Blast\Component\Resource\Metadata\MetadataRegistryInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class ResourceLoader implements LoaderInterface
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
        $allMetadata = $this->metadataRegistry->getAll();
        $routes = new RouteCollection();

        foreach ($allMetadata as $metadata) {
            if ($metadata->hasRouting()) {
                $this->loadMetadataRoutes($metadata, $routes);
            }
            if ($metadata->hasApi()) {
                $this->loadMetadataApi($metadata, $routes);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null): bool
    {
        return 'blast.ressource' === $type;
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

    protected function createRoute()
    {
        return new Route();
    }
}
