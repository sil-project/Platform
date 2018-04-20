<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\GridBundle\Handler;

use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Provider\GridProviderInterface;
use Sylius\Component\Grid\View\GridViewFactoryInterface;
use Sylius\Component\Grid\View\GridViewInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

interface GridHandlerInterface
{
    /**
     * Builds a grid instance for configured grid named « $gridName ».
     *
     * @param string $gridName
     *
     * @return GridViewInterface
     */
    public function buildGrid(string $gridName): GridViewInterface;

    /**
     * Set the value of gridProvider.
     *
     * @param GridProviderInterface $gridProvider
     */
    public function setGridProvider(GridProviderInterface $gridProvider): void;

    /**
     * Set the value of viewFactory.
     *
     * @param GridViewFactoryInterface $viewFactory
     */
    public function setViewFactory(GridViewFactoryInterface $viewFactory): void;

    /**
     * Set the value of formFactory.
     *
     * @param FormFactoryInterface $formFactory
     */
    public function setFormFactory(FormFactoryInterface $formFactory): void;

    /**
     * Set the value of gridDefinitions.
     *
     * @param array $gridDefinitions
     */
    public function setGridDefinitions(array $gridDefinitions): void;

    /**
     * Set the value of requestStack.
     *
     * @param RequestStack $requestStack
     */
    public function setRequestStack(RequestStack $requestStack): void;

    /**
     * Set the value of defaultPerPage.
     *
     * @param int $defaultPerPage
     */
    public function setDefaultPerPage(int $defaultPerPage): void;
}
