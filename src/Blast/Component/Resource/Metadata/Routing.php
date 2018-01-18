<?php

declare(strict_types=1);

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


    public static function fromArray(array $parameters){

      $routing = new self();
      //enabled if enable=true or if there are parameters
      $routing->enabled = $parameters['enable'] ?? !empty($parameters);
      $routing->path = $parameters['path'];
      $routing->redirect = $parameters['redirect'] ?? 'list';
      $routing->view = ViewReference::fromArray($parameters['view']);
      self::buildActions($routing, $parameters['actions']);
    }
}
