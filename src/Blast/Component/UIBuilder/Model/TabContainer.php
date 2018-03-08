<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Model;

class TabContainer extends UiContainer
{
    public function getTabs()
    {
        return $this->children;
    }

    public function addTab(Tab $tab)
    {
        $this->children[$tab->getName()] = $tab;
    }

    public function addChild(UiModelInterface $child)
    {
        throw new \LogicException(__METHOD__ . ' is not allowed, use addTab() instead.');
    }

    public function renderUsing($renderer, $data)
    {
        return $renderer->renderTabContainer($this, $data);
    }
}
