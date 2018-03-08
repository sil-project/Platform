<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Builder;

class TabBuilder extends BaseBuilder
{
    use GroupAwareTrait,
        CollectionAwareTrait,
        FormAwareTrait,
        FieldAwareTrait,
        FieldGroupAwareTrait;

    public function label(string $label)
    {
        $this->options['label'] = $label;

        return $this;
    }

    public function contentCss(string $classes)
    {
        $this->options['contentCss.classes'] = $classes;

        return $this;
    }

    protected function build()
    {
        return $this->getAbstractFactory()->createTab($this->name, $this->options);
    }

    protected function addInParentModel()
    {
        $this->getParentModel()->addTab($this->getModel());
    }
}
