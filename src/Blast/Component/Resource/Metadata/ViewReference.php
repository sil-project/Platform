<?php

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
class ViewReference
{
    protected $type;
    protected $resource;

    public static function createFrom(string $prefix, string $alias, array $parameters)
    {
        $view = new self();
        $view->type = $parameters['type'];
        $view->resource = $parameters['resource'];

        return $view;
    }

    private function __construct()
    {
    }

    public function getType()
    {
        return $this->type;
    }

    public function getResource()
    {
        return $this->resource;
    }
}
