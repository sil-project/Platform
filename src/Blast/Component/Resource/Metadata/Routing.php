<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
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
class Routing implements RoutingInterface
{
    private $enabled = false;

    private $path;

    private $prefix;

    private $redirect = 'list';

    private $only = [];
    /**
     * @var array|RoutingAction[]
     */
    private $actions;

    public static function disabled()
    {
        return new self();
    }

    public static function createFrom(string $prefix, string $alias, array $parameters)
    {
        $routing = new self();
        //enabled if enable=true or if there are parameters
        $routing->enabled = $parameters['enable'] ?? !empty($parameters);
        $routing->path = $parameters['path'];
        $routing->redirect = $parameters['redirect'] ?? 'list';
        $routing->view = self::buildView($prefix, $alias, $parameters);
        self::buildActions($routing, $parameters['actions']);

        return $routing;
    }

    protected static function buildView(string $prefix, string $alias, array $parameters)
    {
        $type = 'template';
        $resource = $prefix . '.view.' . $alias;
        $default = ['type' => 'template', 'resource' => $prefix . '.view.' . $alias];
        $options = $parameters['view'] ?? $default;

        return ViewReference::createFrom($prefix, $alias, $options);
    }

    protected static function buildActions(self $routing, array $actions)
    {
    }

    public function getPath()
    {
        return $this->path;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function getView()
    {
        return $this->view;
    }
}
