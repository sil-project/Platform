<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Model;

abstract class UiContainer extends UiModel
{
    protected $children = [];

    public function getChildren()
    {
        return $this->children;
    }

    public function addChild(UiModelInterface $child)
    {
        $this->children[$child->getName()] = $child;
    }

    public function findChildByName(string $name, bool $deep = false): ?UiModelInterface
    {
        if (array_key_exists($name, $this->children)) {
            return $this->children[$name];
        }
        if (!$deep) {
            return null;
        }
        foreach ($this->children as $child) {
            print_r("\n" . $child->getName());
            $result = $child->findChildByName($name, $deep);
            if (null !== $result) {
                return $result;
            }
        }

        return null;
    }
}
