<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\MenuBundle\Model;

interface ItemInterface
{
    /**
     * @return string Item identifier
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @param string $label
     */
    public function setLabel(string $label): void;

    /**
     * @return string|null optionnal icon name
     */
    public function getIcon(): ?string;

    /**
     * @param string $icon
     */
    public function setIcon(string $icon): void;

    /**
     * @return string|null item's route name
     */
    public function getRoute(): ?string;

    /**
     * @param string $route
     */
    public function setRoute(string $route): void;

    /**
     * @return int|null the item sorting order index
     */
    public function getOrder(): ?int;

    /**
     * @param int $order
     */
    public function setOrder(int $order): void;

    /**
     * @return bool item should be displayed ?
     */
    public function getDisplay(): bool;

    /**
     * @param bool $display
     */
    public function setDisplay(bool $display): void;

    /**
     * @return array the representation of item as an array
     */
    public function toArray(): array;
}
