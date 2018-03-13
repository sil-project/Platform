<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\MenuBundle\Builder;

use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class SimpleMenuBuilder
{
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

    public function createGlobalSidebarMenu()
    {
        $menuTree = $this->loader->load();

        $this->sortMenuItems($menuTree);

        return $menuTree;
    }

    private function sortMenuItems($item)
    {
        $children = $item->getChildren();

        uasort($children, function ($item1, $item2) {
            return $item1->getOrder() <=> $item2->getOrder();
        });

        $item->setChildren($children);

        foreach ($children as $child) {
            $this->sortMenuItems($child);
        }
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
     * @param $loader
     */
    public function setLoader($loader): void
    {
        $this->loader = $loader;
    }
}
