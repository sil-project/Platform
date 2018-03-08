<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Builder;

use Blast\Component\UIBuilder\Factory\AbstractFactoryInterface;
use Blast\Component\UIBuilder\Model\UiModelInterface;

abstract class AbstractBuilder implements BuilderInterface
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var UiModelInterface
     */
    protected $model;

    /**
     * @var BuilderInterface
     */
    protected $parent;

    /**
     * @var AbstractFactoryInterface
     */
    protected $abstractFactory;

    /**
     * @var array
     */
    protected $options = [];

    public function __construct(?BuilderInterface $parent, AbstractFactoryInterface $abstractFactory, string $name)
    {
        $this->parent = $parent;
        $this->abstractFactory = $abstractFactory;
        $this->name = $name;
    }

    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return AbstractFactoryInterface
     */
    public function getAbstractFactory()
    {
        return $this->abstractFactory;
    }

    /**
     * @return BuilderInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return ModelInterface
     */
    public function getParentModel()
    {
        return $this->getParent()->getModel();
    }

    protected function findChildModelByName(string $name)
    {
        return $this->getModel()->findChildByName($name);
    }

    /**
     * @return UiModelInterface
     */
    public function getModel(): UiModelInterface
    {
        if (null === $this->model) {
            $this->model = $this->build();
        }

        return $this->model;
    }

    public function setModel(UiModelInterface $model)
    {
        $this->model = $model;
    }

    protected function addInParentModel()
    {
        $this->getParentModel()->addChild($this->getModel());
    }

    /**
     * @return BuilderInterface
     */
    public function end()
    {
        if (null !== $this->parent) {
            $this->addInParentModel();

            return $this->getParent();
        } else {
            return $this;
        }
    }

    abstract protected function build();
}
