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

namespace Blast\UtilsBundle\Hook\Hooks\CustomFilters;

use Blast\UtilsBundle\Hook\Component\AbstractHook;
use Blast\UtilsBundle\Repository\CustomFilterRepository;

class Save extends AbstractHook
{
    protected $hookName = 'admin.custom_filters.save';
    protected $template = 'BlastUtilsBundle:Hook/CustomFilters:save.html.twig';

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

        $currentFitler = $customFilterRepository->findCurrentFilter($routeName, $filterName);

        $this->templateParameters = [
            'currentFilter' => $currentFitler,
        ];
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
