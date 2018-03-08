<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Builder;

class CollectionBuilder extends GroupBuilder
{
    use FieldAwareTrait;

    public function type($type)
    {
        $this->options['type'] = $type;

        return $this;
    }

    public function valueAccessor($valueAccessor)
    {
        $this->options['valueAccessor'] = $valueAccessor;

        return $this;
    }

    public function build()
    {
        return $this->getAbstractFactory()->createCollection($this->name, $this->options);
    }
}
