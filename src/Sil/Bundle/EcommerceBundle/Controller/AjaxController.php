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

namespace Sil\Bundle\EcommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * @author Romain SANCHEZ <romain.sanchez@libre-informatique.fr>
 */
class AjaxController extends Controller
{
    /**
     * Edit order field.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function orderInlineEditAction(Request $request)
    {
        $value = $request->get('value');
        $manager = $this->getDoctrine()->getManager();
        $repo = $manager->getRepository('SilEcommerceBundle:Order');

        $order = $repo->find($request->get('id'));

        $propertyAccessor = new PropertyAccessor();
        $propertyAccessor->setValue($order, $request->get('field'), $value);

        $manager->persist($order);
        $manager->flush();

        return new JsonResponse($value);
    }

    /**
     * Increase order item quantity.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addToOrderAction(Request $request)
    {
        return new JsonResponse($this->container->get('sil_ecommerce.order.item_updater')->updateItemCount($request->get('order'), $request->get('item'), true));
    }

    /**
     * Decrease order item quantity.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function removeFromOrderAction(Request $request)
    {
        $updater = $this->container->get('sil_ecommerce.order.item_updater');

        $result = $updater->updateItemCount($request->get('order'), $request->get('item'), false);

        if ($result['lastItem'] != null) {
            $result['message'] = $this->container->get('translator')->trans('There must be at least one item left in the order');
        }

        return new JsonResponse($result);
    }

    public function addNewProductAction(Request $request)
    {
        $newProduct = $this->container
            ->get('sil_ecommerce.order.updater')
            ->addProduct(
                $request->get('orderId'), $request->get('variantId')
            );

        if ($newProduct['item'] === null) {
            // $this->container->get('sonata.core.flashmessage.manager')->
            $this->container->get('session')->getFlashBag()->add('error', 'cannot_edit_order_because_of_state');
        }

        return new JsonResponse('ok');
    }

    public function updateOrderItemBulkQuatityAction(Request $request)
    {
        $updater = $this->container->get('sil_ecommerce.order.item_updater');

        $result = $updater->updateItemCount($request->get('order'), $request->get('item'), true, 1000 * $request->get('bulkQuantity'));

        if ($result['lastItem'] != null) {
            $result['message'] = $this->container->get('translator')->trans('There must be at least one item left in the order');
        }

        return new JsonResponse($result);
    }
}
