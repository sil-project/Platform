<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\MenuBundle\Builder;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class MenuBuilder
{
    private $factory;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var AuthorizationChecker
     */
    private $authorizationChecker;

    /**
     * @var object
     */
    private $loader;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createGlobalSidebarMenu(RequestStack $requestStack)
    {
        $menuTree = $this->loader->load();

        $silRoot = $this->factory->createItem($menuTree->getId());

        // Handle app root menu items

        $appRoot = $menuTree->getChild('root');

        $this->sortMenuItems($appRoot->getChildren());

        foreach ($appRoot->getChildren() as $menuItem) {
            $this->buildKnpMenu($menuItem, $silRoot);
        }

        // Handle app settings menu items

        $settingsRoot = $menuTree->getChild('settings');

        $settingsKnpNode = $silRoot->addChild($settingsRoot->getId());
        $this->setIcon($settingsKnpNode, 'sliders');

        $this->sortMenuItems($settingsRoot->getChildren());

        foreach ($settingsRoot->getChildren() as $menuItem) {
            $this->buildKnpMenu($menuItem, $settingsKnpNode);
        }

        // Stock settings
        /*
        $submenu = $menu->addChild('sil.stock.menu_label.stock_management_settings');
        $submenu->addChild('sil.stock.menu_label.warehouses', ['route' => 'admin_stock_warehouse_list']);
        $submenu->addChild('sil.stock.menu_label.locations', ['route' => 'admin_stock_location_list']);
        $submenu->addChild('sil.stock.menu_label.uom_types', ['route' => 'admin_stock_uomtype_list']);
        $submenu->addChild('sil.stock.menu_label.uoms', ['route' => 'admin_stock_uom_list']);
        */

        return $silRoot;
    }

    private function buildKnpMenu($item, $knpNode)
    {
        // Handling role(s) access of item
        if (count($item->getRoles()) > 0) {
            $item->setDisplay(false);
            foreach ($item->getRoles() as $role) {
                if ($this->authorizationChecker->isGranted($role, $this->tokenStorage->getToken()->getUser())) {
                    $item->setDisplay(true);
                }
            }
        }

        if ($item->getDisplay() === true) {
            $currentKnpNode = $knpNode->addChild($item->getId(), [$item->getRoute()]);

            if ($item->getIcon() !== null) {
                $this->setIcon($currentKnpNode, $item->getIcon());
            }

            if (count($item->getChildren()) > 0) {
                // Sorting current menu items
                $this->sortMenuItems($item->getChildren());

                foreach ($item->getChildren() as $child) {
                    $this->buildKnpMenu($child, $currentKnpNode);
                }
            } else {
                $currentKnpNode->setExtra('on_top', true);
            }
        }
    }

    private function setIcon($node, $icon) {
        $node->setExtra('icon', sprintf('<i class="fa fa-%s"></i>', $icon));
    }

    private function sortMenuItems($items) {
        uasort($items, function ($item1, $item2) {
            return $item1->getOrder() <=> $item2->getOrder();
        });
    }

    /**
     * @param TokenStorage $tokenStorage
     */
    public function setTokenStorage(TokenStorage $tokenStorage): void
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param AuthorizationChecker $authorizationChecker
     */
    public function setAuthorizationChecker(AuthorizationChecker $authorizationChecker): void
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param array $menu
     */
    public function setRootMenu($menu): void
    {
        $this->rootMenu = $menu;
    }

    /**
     * @param array $menu
     */
    public function setSettingsMenu($menu): void
    {
        $this->settingsMenu = $menu;
    }

    /**
     * @param $loader
     */
    public function setLoader($loader): void
    {
        $this->loader = $loader;
    }
}
