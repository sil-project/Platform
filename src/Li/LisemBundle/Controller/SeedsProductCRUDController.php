<?php

/*
 * This file is part of the Lisem Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\Controller;

use Sil\Bundle\EcommerceBundle\Controller\ProductCRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class SeedsProductCRUDController extends ProductCRUDController
{
    public function createAction($object = null)
    {
        $request = $this->getRequest();

        if (null !== $request->get('btn_create_for_variety')) {
            $object = $this->admin->getNewInstance();
            $form = $this->admin->getForm();
            $form->setData($object);
            $form->handleRequest($request);
            $variety_id = $form->getData()->getVariety()->getId();
            $url = $this->admin->generateUrl('create', ['variety_id' => $variety_id]);

            return new RedirectResponse($url);
        }

        return parent::createAction($object);
    }
}
