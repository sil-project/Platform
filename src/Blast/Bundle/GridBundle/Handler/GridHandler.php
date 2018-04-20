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
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\Provider\GridProviderInterface;
use Sylius\Component\Grid\View\GridViewFactoryInterface;
use Sylius\Component\Grid\View\GridViewInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;

class GridHandler implements GridHandlerInterface
{
    /**
     * @var GridProviderInterface
     */
    protected $gridProvider;

    /**
     * @var GridViewFactoryInterface
     */
    protected $viewFactory;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var array
     */
    protected $gridDefinitions;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var int
     */
    protected $defaultPerPage = 20;

    public function buildGrid(string $gridName): GridViewInterface
    {
        /** @var Grid */
        $grid = $this->gridProvider->get($gridName);
        $currentRequest = $this->requestStack->getCurrentRequest();

        $parameters = $this->handleGridParameters($gridName, $currentRequest);

        $gridView = $this->viewFactory->create($grid, $parameters);

        $pager = $gridView->getData();

        $pager->setCurrentPage($parameters->get('page'));
        $pager->setMaxPerPage($parameters->get('limit'));

        return $gridView;
    }

    protected function handleGridParameters(string $gridName, Request $request): Parameters
    {
        // Merging parameters to set default values for pagination
        $mergedParameters = array_merge(
            $request->query->all(),
            [
                'page' => (int) $request->get('page', 1),
                'limit'=> (int) $request->get('limit', $this->defaultPerPage),
            ]
        );

        // Handle manual change of « limit » parameter to avoid huge limit size.
        $currentGridDefinition = $this->gridDefinitions[$gridName];
        $limits = $currentGridDefinition['limits'];

        if (!in_array($mergedParameters['limit'], $limits)) {
            $mergedParameters['limit'] = end($limits);
        }

        return new Parameters($mergedParameters);
    }

    /**
     * Set the value of gridProvider.
     *
     * @param GridProviderInterface $gridProvider
     */
    public function setGridProvider(GridProviderInterface $gridProvider): void
    {
        $this->gridProvider = $gridProvider;
    }

    /**
     * Set the value of viewFactory.
     *
     * @param GridViewFactoryInterface $viewFactory
     */
    public function setViewFactory(GridViewFactoryInterface $viewFactory): void
    {
        $this->viewFactory = $viewFactory;
    }

    /**
     * Set the value of formFactory.
     *
     * @param FormFactoryInterface $formFactory
     */
    public function setFormFactory(FormFactoryInterface $formFactory): void
    {
        $this->formFactory = $formFactory;
    }

    /**
     * Set the value of gridDefinitions.
     *
     * @param array $gridDefinitions
     */
    public function setGridDefinitions(array $gridDefinitions): void
    {
        $this->gridDefinitions = $gridDefinitions;
    }

    /**
     * Set the value of requestStack.
     *
     * @param RequestStack $requestStack
     */
    public function setRequestStack(RequestStack $requestStack): void
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Set the value of defaultPerPage.
     *
     * @param int $defaultPerPage
     */
    public function setDefaultPerPage(int $defaultPerPage): void
    {
        $this->defaultPerPage = $defaultPerPage;
    }
}
