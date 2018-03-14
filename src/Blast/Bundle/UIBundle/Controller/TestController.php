<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UIBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function testAction(Request $request)
    {
        $breadcrumb = $this->container->get('blast_ui.service.breadcrumb_handler');

        $breadcrumb
            ->addItem('item1', '/')
            ->addItem('item2', $this->generateUrl('blast_ui_test'))
        ;

        return $this->render('@BlastUI/_test.html.twig');
    }
}
