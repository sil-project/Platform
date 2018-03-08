<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Model;

use Symfony\Component\PropertyAccess\PropertyAccess;

class Collection extends Group
{
    protected $valueAccessor;

    public function getValueAccessor()
    {
        return   $this->valueAccessor;
    }

    public function setValueAccessor(string $valueAccessor)
    {
        $this->valueAccessor = $valueAccessor;
    }

    public function valueFrom($data)
    {
        if (null === $this->valueAccessor) {
            return $data;
        }

        return PropertyAccess::createPropertyAccessor()->getValue($data, $this->valueAccessor);
    }

    public function addChild(UiModelInterface $child)
    {
        parent::addChild(new CollectionItem($child));
    }

    public function renderUsing($renderer, $data)
    {
        return $renderer->renderCollection($this, $data);
    }
}
