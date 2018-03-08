<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Builder;

class TabContainerBuilder extends BaseBuilder
{
    /**
     * @var array
     */
    protected $tabs = [];

    public function tab($name)
    {
        return new TabBuilder($this, $this->getAbstractFactory(), $name);
    }

    protected function build()
    {
        return $this->getAbstractFactory()->createTabContainer($this->name, $this->options);
    }

    public function end()
    {
        $this->getParentModel()->addChild($this->getModel());

        return parent::end();
    }
}
