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
class ClassMap implements ClassMapInterface
{
    private $model;
    private $repository;

    private function __construct()
    {
    }

    public static function fromArray(array $parameters)
    {
        $classMap = new self();
        $classMap->model = $parameters['model'] ?? null;
        $classMap->repository = $parameters['repository'] ?? null;

        return $classMap;
    }

    public function hasModel()
    {
        return null !== $this->model;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function hasRepository()
    {
        return null !== $this->repository;
    }

    public function getRepository()
    {
        return $this->repository;
    }
}
