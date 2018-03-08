<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Model;

class CssConfig
{
    protected $id;
    protected $classes = [];

    public function getId()
    {
        return $this->id;
    }

    public function getClasses()
    {
        return $this->classes;
    }

    public function setClasses($classes)
    {
        $this->classes = $classes;
    }
}
