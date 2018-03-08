<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Model;

class CollectionItem extends UiModel
{
    protected $field;

    public function __construct(Field $field)
    {
        $this->field = $field;
        parent::__construct($field->getName() . '.item', []);
    }

    public function getField()
    {
        return $this->field;
    }

    public function renderUsing($renderer, $data)
    {
        return $renderer->renderCollectionItem($this, $data);
    }

    public function findChildByName(string $name, bool $deep = false): ?UiModelInterface
    {
        return ($this->field->getName() == $name) ? $this->field : null;
    }
}
