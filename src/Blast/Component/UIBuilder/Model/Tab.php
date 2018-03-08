<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Model;

class Tab extends UiContainer
{
    protected $label;
    protected $icon;
    protected $description;
    protected $order = 0;
    protected $contentCss;

    public function __construct(string $name, array $options = [])
    {
        $this->contentCss = new CssConfig();
        parent::__construct($name, $options);
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function getContentCss()
    {
        return $this->contentCss;
    }

    public function getDescription()
    {
        return 'drgr drg drg drg drg drgdrgdr gdr gdrgdrg drg drg drg'; //$this->description;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder(int $order)
    {
        $this->order = $order;
    }

    public function renderUsing($renderer, $data)
    {
        return $renderer->renderTab($this, $data);
    }
}
