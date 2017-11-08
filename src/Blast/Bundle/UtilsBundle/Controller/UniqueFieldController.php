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

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UniqueFieldController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \Exception
     */
    public function checkAvailabilityAction(Request $request)
    {
        $response = ['available' => true];
        $translator = $this->get('translator');
        $repo = $this->getDoctrine()->getManager()->getRepository($request->get('className'));

        $object = $repo->findOneBy([$request->get('fieldName') => $request->get('fieldValue')]);

        if ($object != null) {
            $response['available'] = false;
            $response['error'] = $this->get('translator')->trans('already exists');

            if ($request->get('returnLink') == true) {
                $adminPool = $this->container->get('sonata.admin.pool');
                $admin = $adminPool->getAdminByAdminCode($request->get('adminCode'));

                $response['link'] = $admin->generateObjectUrl('edit', $object);
                $response['message'] = $translator->trans('Edit object');
            }
        }

        return new JsonResponse($response, 200);
    }
}
