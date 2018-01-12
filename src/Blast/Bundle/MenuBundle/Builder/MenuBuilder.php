<?php

/*
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

        $silRoot = $this->factory->createItem($menuTree->getLabel());

        // Handle app root menu items

        $appRoot = $menuTree->getChild('root');

        $this->sortMenuItems($appRoot);

        foreach ($appRoot->getChildren() as $menuItem) {
            $this->buildKnpMenu($menuItem, $silRoot);
        }

        // Handle app settings menu items

        $settingsRoot = $menuTree->getChild('settings');
        $settingsRoot->setLabel('blast.menu_label.application_settings');

        $settingsKnpNode = $silRoot->addChild($settingsRoot->getLabel());
        $this->setIcon($settingsKnpNode, 'sliders');

        $items = $this->sortMenuItems($settingsRoot);

        foreach ($settingsRoot->getChildren() as $menuItem) {
            $this->buildKnpMenu($menuItem, $settingsKnpNode);
        }

        return $silRoot;
    }

    private function buildKnpMenu($item, $knpNode)
    {
        // Handling role(s) access of item
        if (count($item->getRoles()) > 0) {
            $item->setDisplay(false);
            foreach ($item->getRoles() as $role) {
                /* @todo: find a cleaner way to avoid null getToken and getUser Execption */
                if ($this->tokenStorage->getToken()) {
                    if ($this->authorizationChecker->isGranted($role, $this->tokenStorage->getToken()->getUser())) {
                        $item->setDisplay(true);
                    }
                }
            }
        }

        if ($item->getDisplay() === true) {
            $currentKnpNode = $knpNode->addChild($item->getLabel(), ['route' => $item->getRoute()]);

            if ($item->getIcon() !== null) {
                $this->setIcon($currentKnpNode, $item->getIcon());
            }

            if (count($item->getChildren()) > 0) {
                // Sorting current menu items
                $this->sortMenuItems($item);

                foreach ($item->getChildren() as $child) {
                    $this->buildKnpMenu($child, $currentKnpNode);
                }
            } else {
                $currentKnpNode->setExtra('on_top', true);
            }
        }
    }

    private function setIcon($node, $icon)
    {
        $node->setExtra('icon', sprintf('<i class="fa fa-%s"></i>', $icon));
    }

    private function sortMenuItems($item)
    {
        $children = $item->getChildren();
        uasort($children, function ($item1, $item2) {
            return $item1->getOrder() <=> $item2->getOrder();
        });

        return $item->setChildren($children);
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
