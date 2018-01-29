<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UtilsBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class UniqueFieldController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function checkAvailabilityAction(Request $request)
    {
        $translator = $this->get('translator');
        $checker = $this->get('blast_utils.unique_field_checker');

        $result = $checker->check(
            $request->get('className'),
            $request->get('fieldName'),
            $request->get('fieldValue')
        );

        return new JsonResponse($checker->renderResult($result['object'], $request->get('fieldValue'), $request->get('adminCode')), 200);
    }
}
