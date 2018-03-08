<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Builder;

abstract class BaseBuilder extends AbstractBuilder
{
    public function css(string $classes)
    {
        $this->options['css.classes'] = $classes;

        return $this;
    }

    public function containerCss(string $classes)
    {
        $this->options['container_css.classes'] = $classes;

        return $this;
    }

    public function template(string $template)
    {
        $this->options['template'] = $template;

        return $this;
    }

    public function useBuilder(self $builder)
    {
        $this->model = $builder->getModel();
        $this->options = $builder->getOptions();
        $this->model->setName($this->name);

        return $this;
    }

    public function override(string $childName)
    {
        $childModel = $this->getModel()->findChildByName($childName, true);
        if (null === $childModel) {
            throw new \InvalidArgumentException('child named "' . $childName . '" does not exist');
        }
        $builderClass = BuilderClassMap::getBuilderClassFromModel($childModel);

        return (new BuilderOverride($this, $builderClass, $childModel))->getBuilder();
    }
}
