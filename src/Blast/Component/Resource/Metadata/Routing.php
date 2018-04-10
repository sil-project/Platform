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
use InvalidArgumentException;

/**
 * Description of Metadata.
 *
 * @author glenn
 */
class Routing implements RoutingInterface
{
    protected $resourceAlias;
    protected $resourcePrefix;
    protected $pathPrefix;
    protected $enabled = false;
    protected $path;
    protected $templatePath;
    protected $redirect = 'list';
    protected $defaultActions = [];
    /**
     * @var array|RoutingAction[]
     */
    protected $actions;

    public static function createFrom(string $resourcePrefix, string $resourceAlias, array $parameters)
    {
        $routing = new self();
        //enabled if enable=true or if there are parameters
        $routing->enabled = $parameters['enable'] ?? !empty($parameters);
        $routing->resourceAlias = $resourceAlias;
        $routing->resourcePrefix = $resourcePrefix;
        $routing->path = $parameters['base_path'] ?? $resourceAlias;
        $routing->templatePath = $parameters['base_template'] ?? $resourceAlias;
        $routing->pathPrefix = $parameters['prefix'] ?? '';
        $routing->redirect = $parameters['redirect'] ?? 'list';
        $routing->defaultActions = $parameters['only'] ?? Actions::getActions();
        $routing->view = ViewReference::createFrom($parameters['view'] ?? []);
        self::buildActions($routing, $parameters['actions'] ?? []);

        return $routing;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function getView()
    {
        return $this->view;
    }

    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function getRouteId(string $action): string
    {
        return $this->resourcePrefix . '_' . $this->resourceAlias . '_' . $action;
    }

    public function getPathPrefix()
    {
        return $this->pathPrefix;
    }

    /**
     * [buildActions description].
     *
     * @param [type] $routing [description]
     * @param [type] $actions [description]
     * @throw InvalidArgumentException
     *
     * @return [type] [description]
     */
    protected static function buildActions($routing, $actions)
    {
        $defaultActions = $routing->defaultActions;
        if (!Actions::containsOnlyActions($defaultActions)) {
            throw new InvalidArgumentException('Only predefined default actions are allowed in "routing.only" parameter');
        }

        //create default actions
        foreach ($defaultActions as $actionName) {
            $routing->actions[$actionName] = RoutingAction::createDefault($actionName, $routing);
        }
        //create custom actions and merge with default action if necessary
        foreach ($actions as $actionName => $options) {
            if (isset($routing->actions[$actionName])) {
                $action = $routing->actions[$actionName];
                $action->updateOptions($options, $routing);
            } else {
                $action = RoutingAction::createFrom($actionName, $options, $routing);
                $routing->actions[$actionName] = $action;
            }
        }
    }
}
