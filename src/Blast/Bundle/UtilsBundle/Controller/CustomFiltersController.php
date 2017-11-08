<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UtilsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CustomFiltersController extends Controller
{
    private $customFilterClass;

    public function saveFilterAction(Request $request)
    {
        $filterName = $request->request->get('filterName');
        $routeName = $request->request->get('routeName');
        $routeParameters = json_decode($request->request->get('routeParameters'));
        $filterParameters = json_decode($request->request->get('filterParameters'));
        $user = $this->getUser();

        $customFilterRepository = $this->getDoctrine()->getRepository($this->getCustomFilterCLass());

        $redirectUrl = $request->headers->get('referer');

        if (!$filterName) {
            $this->flashTrans('error', 'Please define a name for your filter');
        } else {
            $existingCustomFilter = $customFilterRepository->findOneBy([
                'routeName' => $routeName,
                'name'      => $filterName,
                'user'      => $user,
            ]);

            if ($existingCustomFilter) {
                $this->flashTrans('error', 'Custom filter with this name already exists');
            } else {
                $filter = $customFilterRepository->createNewCustomFilter($filterName, $routeName, $routeParameters, $filterParameters, $user);

                $this->flashTrans('success', 'Custom filter successfully saved');

                $redirectUrl = $this->generateUrl(
                    $filter->getRouteName(),
                    array_merge(
                        $filter->getRouteParameters(),
                        [
                            'filter'     => $filter->getFilterParameters(),
                            'filterName' => $filter->getName(),
                        ]
                    )
                );
            }
        }

        return new RedirectResponse($redirectUrl);
    }

    public function deleteFilterAction(Request $request)
    {
        $filterId = $request->request->get('filterId', null);

        $customFilterRepository = $this->getDoctrine()->getRepository($this->getCustomFilterCLass());

        $filter = $customFilterRepository->find($filterId);

        $redirectUrl = $request->headers->get('referer');

        if (!$filter) {
            $this->flashTrans('error', 'Cannot find filter with ID « %s »');
        } else {
            if ($filter->getUser() !== null && $this->getUser() === $filter->getUser()) {
                $this->getDoctrine()->getManager()->remove($filter);
                $this->getDoctrine()->getManager()->flush($filter);
            } else {
                $this->flashTrans('error', 'This filter doesn\'t belong to you, you cannot delete it');
            }
        }

        return new RedirectResponse($redirectUrl);
    }

    public function getCustomFilterCLass()
    {
        return $this->customFilterClass = $this->container->getParameter('blast_utils')['features']['customFilters']['class'];
    }

    private function flashTrans($type, $messageKey, $catalog = 'messages', $replacements = [])
    {
        $this->addFlash($type, $this->container->get('translator')->trans($messageKey, $replacements, $catalog));
    }
}
