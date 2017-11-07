<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UtilsBundle\Hook\Hooks\CustomFilters;

use Blast\Bundle\UtilsBundle\Hook\Component\AbstractHook;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Blast\Bundle\UtilsBundle\Repository\CustomFilterRepository;

class Dropdown extends AbstractHook
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var array
     */
    private $parameters = [];

    public function handleParameters($hookParameters)
    {
        if ($this->parameters['features']['customFilters']['enabled'] === false) {
            $this->disable();
        }

        /** @var CustomFilterRepository $customFilterRepository */
        $customFilterRepository = $this->em->getRepository('BlastUtilsBundle:CustomFilter');

        $request = $this->requestStack->getMasterRequest();

        $routeName = $request->get('_route');
        $filterName = $request->query->get('filterName');

        $user = $this->tokenStorage->getToken()->getUser();

        $currentFitler = $customFilterRepository->findCurrentFilter($routeName, $filterName);

        $this->templateParameters = [
            'customFilters' => [
                'global'        => $customFilterRepository->findGlobalFilters($routeName),
                'user'          => $customFilterRepository->findUserCustomFilters($routeName, $user),
                'currentFilter' => $currentFitler,
            ],
        ];
    }

    /**
     * @param TokenStorage tokenStorage
     *
     * @return self
     */
    public function setTokenStorage(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;

        return $this;
    }

    /**
     * @param array parameters
     *
     * @return self
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }
}
