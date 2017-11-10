<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Controller;

use Sylius\Bundle\OrderBundle\Controller\OrderItemController as BaseOrderItemController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SyliusOrderItemController extends BaseOrderItemController
{
    public function addAction(Request $request): Response
    {
        $this->handleBulkForm($request);

        return parent::addAction($request);
    }

    private function handleBulkForm(Request $request)
    {
        if ($request->request->get('product-is-bulk') === '1') {
            $bulkWeight = $request->request->get('bulk-weight');
            if ($bulkWeight !== null && $bulkWeight !== '') {
                $this->get('sil_ecommerce.event_listener.sylius_order_item_controller')->setBulkInformations([
                    'bulk-surface'            => $request->request->get('bulk-surface'),
                    'bulk-surface-unit'       => $request->request->get('bulk-surface-unit'),
                    'bulk-weight'             => $bulkWeight,
                    'bulk-weight-unit'        => $request->request->get('bulk-weight-unit'),
                    'product-variety-density' => $request->request->get('product-variety-density'),
                    'product-variety-tkw'     => $request->request->get('product-variety-tkw'),
                ]);
            } else {
                $syliusAddToCart = $request->request->get('sylius_add_to_cart');
                // Simulate a form error because bulk-weight is not filled
                $syliusAddToCart['cartItem']['variant'] = null;
                $syliusAddToCart['cartItem']['quantity'] = null;

                $request->request->set('sylius_add_to_cart', $syliusAddToCart);
            }
        }
    }
}
