<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Model;

use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class UiModel implements UiModelInterface
{
    protected $name;
    protected $template;
    protected $options;
    protected $css;
    protected $containerCss;

    public function __construct(string $name, array $options = [])
    {
        $this->name = $name;
        $this->css = new CssConfig();
        $this->containerCss = new CssConfig();
        $this->configure($options);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getCss()
    {
        return $this->css;
    }

    public function getContainerCss()
    {
        return $this->containerCss;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    public function mergeWith(self $model)
    {
        $properties = get_object_vars($this);
        foreach ($properties as $prop => $value) {
            if (is_array($this->$prop)) {
                $this->$prop = array_merge($this->$prop, $model->$prop);
            } else {
                $this->$prop = $model->$prop;
            }
        }
    }

    protected function configure(array $options = [])
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($options as $prop =>$value) {
            if ($accessor->isWritable($this, $prop)) {
                $accessor->setValue($this, $prop, $value);
            }
        }
    }

    public function findChildByName(string $name, bool $deep = false): ?UiModelInterface
    {
        return null;
    }
}
