<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Builder;

trait FormAwareTrait
{
    public function form($name)
    {
        return new FormBuilder($this, $this->getAbstractFactory(), $name);
    }
}
