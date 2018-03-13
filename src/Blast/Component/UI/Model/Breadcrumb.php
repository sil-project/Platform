<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UI\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Breadcrumb
{
    /**
     * Collection of breadcrumb items.
     *
     * @var Collection|BreadcrumbItems[]
     */
    protected $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @return array|BreadcrumbItem[]
     */
    public function getItems(): array
    {
        return $this->items->getValues();
    }

    /**
     * @param BreadcrumbItem $item
     *
     * @throws InvalidArgumentException
     */
    public function addItem(BreadcrumbItem $item): void
    {
        if ($this->items->contains($item)) {
            throw new InvalidArgumentException(sprintf('Item « %s » is already in breadcrumb', $item));
        }

        $this->items->forAll(function ($key, &$item) {
            $item->setCurrent(false);

            return true;
        });

        $item->setCurrent();
        $this->items->add($item);
    }

    /**
     * @param BreadcrumbItem $item
     *
     * @throws InvalidArgumentException
     */
    public function removeItem(BreadcrumbItem $item): void
    {
        if (!$this->items->contains($item)) {
            throw new InvalidArgumentException(sprintf('Item « %s » is not in breadcrumb', $item));
        }
        $this->items->removeElement($item);

        $this->items->forAll(function ($key, &$item) {
            $item->setCurrent(false);

            return true;
        });

        $this->items->last()->setCurrent();
        $this->items->first();
    }
}
