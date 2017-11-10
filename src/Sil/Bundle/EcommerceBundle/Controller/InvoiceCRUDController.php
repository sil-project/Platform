<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Controller;

use Blast\Bundle\CoreBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class InvoiceCRUDController extends CRUDController
{
    /**
     * @param Request $request
     * @param mixed   $object
     *
     * @return Response|null
     */
    protected function preCreate(Request $request, $object)
    {
        throw new AccessDeniedException();
    }

    /**
     * @param Request $request
     * @param mixed   $object
     *
     * @return Response|null
     */
    protected function preEdit(Request $request, $object)
    {
        throw new AccessDeniedException();
    }

    /**
     * @param Request $request
     * @param mixed   $object
     *
     * @return Response|null
     */
    protected function preDelete(Request $request, $object)
    {
        throw new AccessDeniedException();
    }

    /**
     * @param mixed $object
     *
     * @return Response|null
     */
    protected function preDuplicate($object)
    {
        throw new AccessDeniedException();
    }

    /**
     * show file action.
     *
     * @return Response
     */
    public function showFileAction()
    {
        $id = $this->getRequest()->get($this->admin->getIdParameter());
        $invoice = $this->admin->getObject($id);

        if (!$invoice) {
            throw $this->createNotFoundException(sprintf('Unable to find the invoice with id : %s', $id));
        }

        $this->admin->checkAccess('show', $invoice);

        return new Response(
            $invoice->getFile(),
            200,
            ['Content-Type' => 'application/pdf']
        );
    }

    /**
     * Generate invoice.
     *
     * @param Request $request
     * @param string  $order_id
     *
     * @return JsonResponse
     */
    public function generateAction(Request $request, $order_id)
    {
        $manager = $this->getDoctrine()->getManager();
        $repo = $manager->getRepository('SilEcommerceBundle:Order');

        $order = $repo->find($order_id);
        if (!$order) {
            throw $this->createNotFoundException(sprintf('Unable to find the order with id : %s', $order_id));
        }

        $this->admin->checkAccess('create');

        if ($order->getCheckoutState() != 'completed') {
            throw new \Exception('Checkout is not completed');
        }

        if (count($order->getInvoices()) > 0) {
            throw new \Exception('Invoice has already been generated');
        }

        $invoiceFactory = $this->container->get('sil_ecommerce.factory.invoice');
        $invoice = $invoiceFactory->createForOrder($order);
        $manager->persist($invoice);
        $manager->flush();

        $order->addInvoice($invoice);

        $html = $this->renderView('SilEcommerceBundle:OrderAdmin/Show:_invoices_inner.html.twig', ['object' => $order]);

        return new JsonResponse(['html' => $html]);
    }
}
