<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Builder;

class InputFieldBuilder extends FieldBuilder
{
    public function required($bool = true)
    {
        $this->options['required'] = $bool;

        return $this;
    }

    public function placeholder($placeholder)
    {
        $this->options['placeholder'] = $placeholder;

        return $this;
    }

    protected function build()
    {
        return $this->getAbstractFactory()->createInputField($this->name, $this->options);
    }
}
