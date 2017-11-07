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

namespace Blast\Bundle\BaseEntitiesBundle\Controller;

use Sil\Bundle\CRMBundle\Entity\Category;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;

class TreeableCRUDController extends CRUDController
{
    public function listAction(Request $request = null)
    {
        $this->admin->checkAccess('list');

        $preResponse = $this->preList($request);
        if ($preResponse !== null) {
            return $preResponse;
        }

        if ($listMode = $request->get('_list_mode')) {
            $this->admin->setListMode($listMode);
        }

        $datagrid = $this->admin->getDatagrid();

        /** @var Category $item */
        foreach ($datagrid->getResults() as $key => $item) {
            $datagrid->getResults()[$key]->setName(
                str_repeat('- - ', $item->getNodeLevel() - 1) . ' ' .
                $datagrid->getResults()[$key]->getName()
            );
        }

        $formView = $datagrid->getForm()->createView();

        // set the theme for the current Admin Form
        $this->defineFormTheme($formView, $this->admin->getFormTheme());

        return $this->render($this->admin->getTemplate('list'), array(
            'action'     => 'list',
            'form'       => $formView,
            'datagrid'   => $datagrid,
            'csrf_token' => $this->getCsrfToken('sonata.batch'),
        ), null, $request);
    }
}
