<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\MenuBundle\Configuration;

use Blast\Bundle\MenuBundle\Model\Item;
use Blast\Bundle\MenuBundle\Model\ItemInterface;

class Loader
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * Loads menu configuration based on container parameter.
     *
     * @return [type] [description]
     */
    public function load()
    {
        $root = $this->buildRoot();

        foreach ($this->parameters as $menuItemName => $menuItem) {
            $item = Item::fromArray([$menuItemName => ['children' => $menuItem]]);
            $root->addChild($item);
        }

        return $root;
    }

    private function buildRoot(): ItemInterface
    {
        return new Item('sil_menu_root');
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }
}
