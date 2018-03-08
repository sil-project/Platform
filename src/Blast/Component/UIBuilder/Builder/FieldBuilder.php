<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Builder;

class FieldBuilder extends BaseBuilder
{
    public function label(string $label)
    {
        $this->options['label'] = $label;

        return $this;
    }

    public function labelIcon(string $icon)
    {
        $this->options['labelIcon'] = $icon;

        return $this;
    }

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

    public function valueTemplate($valueTemplate)
    {
        $this->options['valueTemplate'] = $valueTemplate;

        return $this;
    }

    protected function build()
    {
        return $this->getAbstractFactory()->createField($this->name, $this->options);
    }
}
