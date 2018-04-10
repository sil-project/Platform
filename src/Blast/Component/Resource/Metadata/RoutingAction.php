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

use Blast\Component\Resource\Actions;

/**
 * Description of Metadata.
 *
 * @author glenn
 */
class RoutingAction
{
    protected $name;
    protected $path;
    protected $action;
    protected $view;
    protected $methods = [];
    protected $controller;
    protected $requirements = [];

    public static function createDefault(string $action, Routing $routing)
    {
        $routingAction = new self();
        $routingAction->action = $action;
        $routingAction->methods = Actions::methodsForAction($action);
        $routingAction->name = $routing->getRouteId($action);
        $routingAction->path = self::buildDefaultPath($routing, $action);

        return $routingAction;
    }

    public static function createFrom(string $action, array $options, Routing $routing)
    {
        $routingAction = new self();
        $routingAction->action = $action;
        $routingAction->name = $routing->getRouteId($action);
        $routingAction->updateOptions($options, $routing);

        return $routingAction;
    }

    protected static function buildDefaultPath(Routing $routing, string $action)
    {
        $identifier = Actions::identifierForAction($action);
        $path = $routing->getPathPrefix() . '/' . $routing->getPath() . '/' . $action;
        if (null !== $identifier) {
            $path .= sprintf('/{%s}', $identifier);
        }

        return $path;
    }

    public function __construct()
    {
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getMethods()
    {
        return $this->methods;
    }

    public function getRequirements()
    {
        return $this->requirements;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function updateOptions(array $options, Routing $routing)
    {
        $this->methods = $options['methods'] ?? $this->methods;
        $this->controller = $options['controller'] ?? $this->controller ?? null;
        $this->path = $options['path'] ?? $this->path ?? self::buildDefaultPath($routing, $this->action);
        $this->requirements = $options['requirements'] ?? $this->requirements;
    }
}
