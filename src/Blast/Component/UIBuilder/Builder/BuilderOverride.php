<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Builder;

use Blast\Component\UIBuilder\Model\UiModelInterface;

class BuilderOverride implements BuilderInterface, UiModelInterface
{
    protected $model;
    protected $builder;

    public function __construct(BuilderInterface $rootBuilder, $builderClass, UiModelInterface $model)
    {
        $this->builder = new $builderClass(null, $rootBuilder->getAbstractFactory(), $model->getName());
        $this->builder->setModel($model);
        $this->model = $model;
    }

    public function getName(): string
    {
        return $this->model->getName();
    }

    public function getBuilder(): BuilderInterface
    {
        return $this->builder;
    }

    public function getModel(): UiModelInterface
    {
        return $this;
    }

    public function addChild($child)
    {
        $this->model->mergeWith($child);
    }

    public function end()
    {
    }
}
