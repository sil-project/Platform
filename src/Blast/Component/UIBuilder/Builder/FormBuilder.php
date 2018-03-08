<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Builder;

class FormBuilder extends BaseBuilder
{
    use TabContainerAwareTrait,
        GroupAwareTrait,
        FieldAwareTrait,
        FieldGroupAwareTrait;

    protected function build()
    {
        return $this->getAbstractFactory()->createForm($this->name, $this->options);
    }
}
