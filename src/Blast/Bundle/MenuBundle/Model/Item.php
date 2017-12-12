<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\MenuBundle\Model;

class Item implements ItemInterface, NestedItemInterface, RolableItemInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $icon;

    /**
     * @var string
     */
    private $route;

    /**
     * @var int
     */
    private $order;

    /**
     * @var bool
     */
    private $display = true;

    /**
     * @var array
     */
    private $children;

    /**
     * @var ItemInterface
     */
    private $parent;

    /**
     * @var array
     */
    private $roles;

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
        $this->label = $id;
        $this->children = [];
        $this->roles = [];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    public function getRoute(): ?string
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    /**
     * @return int
     */
    public function getOrder(): ?int
    {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder(int $order): void
    {
        $this->order = $order;
    }

    /**
     * @return bool
     */
    public function getDisplay(): bool
    {
        return $this->display;
    }

    /**
     * @param bool $display
     */
    public function setDisplay(bool $display): void
    {
        $this->display = $display;
    }

    /**
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param array $children
     */
    public function setChildren(array $children): void
    {
        $this->children = $children;
    }

    /**
     * @param string $childId
     *
     * @return ItemInterface
     */
    public function getChild(string $childId): ?ItemInterface
    {
        if (array_key_exists($childId, $this->children)) {
            return $this->children[$childId];
        } else {
            return null;
        }
    }

    /**
     * @param ItemInterface $child
     */
    public function addChild(ItemInterface $child): void
    {
        if (!array_key_exists($child->getId(), $this->children)) {
            $child->setParent($this);
            $this->children[$child->getId()] = $child;
        }
    }

    /**
     * @param ItemInterface $child
     */
    public function removeChild(ItemInterface $child): void
    {
        if (array_key_exists($child->getId(), $this->children)) {
            unset($this->children[$child->getId()]);
        }
    }

    /**
     * @return ItemInterface
     */
    public function getParent(): ?ItemInterface
    {
        return $this->parent;
    }

    /**
     * @param ItemInterface $parent
     */
    public function setParent(ItemInterface $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @param string $role
     */
    public function addRole(string $role): void
    {
        if (!array_key_exists($role, $this->roles)) {
            $this->roles[$role] = $role;
        }
    }

    /**
     * @param string $role
     */
    public function removeRole(string $role): void
    {
        if (array_key_exists($role, $this->roles)) {
            unset($this->roles[$role]);
        }
    }

    /**
     * @return ItemInterface
     */
    public function getRoot(): ItemInterface
    {
        return $this->parent !== null ? $this->parent->getRoot() : $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $id;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            $this->id => [
                'label'   => $this->label,
                'icon'    => $this->icon,
                'route'   => $this->route,
                'order'   => $this->order,
                'display' => $this->display,
                'roles'   => $this->roles,
            ],
        ];
    }

    /**
     * @param array         $itemArray
     * @param ItemInterface $parent
     *
     * @return Item
     */
    public static function fromArray(array $itemArray, ItemInterface $parent = null): ItemInterface
    {
        if (count($itemArray) > 1) {
            throw new \Exception(sprintf('array $itemArray must have only one key, got %i key(s)', count($itemArray)));
        }

        $itemName = (string) array_keys($itemArray)[0];

        $item = new self($itemName);

        if (isset($itemArray[$itemName]['label'])) {
            $item->setLabel($itemArray[$itemName]['label']);
        }

        if (isset($itemArray[$itemName]['icon'])) {
            $item->setIcon($itemArray[$itemName]['icon']);
        }

        if (isset($itemArray[$itemName]['route'])) {
            $item->setRoute($itemArray[$itemName]['route']);
        }

        if (isset($itemArray[$itemName]['order'])) {
            $item->setOrder($itemArray[$itemName]['order']);
        }

        if (isset($itemArray[$itemName]['display'])) {
            $item->setDisplay($itemArray[$itemName]['display']);
        }

        if (isset($itemArray[$itemName]['roles'])) {
            $item->setRoles($itemArray[$itemName]['roles']);
        }

        if ($parent !== null) {
            $item->setParent($parent);
        }

        if (isset($itemArray[$itemName]['children']) && count($itemArray[$itemName]['children']) > 0) {
            foreach ($itemArray[$itemName]['children'] as $childName => $child) {
                $item->addChild(self::fromArray([$childName => $child], $parent));
            }
        }

        return $item;
    }
}
