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

namespace Librinfo\EcommerceBundle\Controller;

//use Sylius\Component\Core\PaymentTransitions;
//use Sylius\Component\Core\ShipmentTransitions;
use Blast\CoreBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Librinfo\EcommerceBundle\StateMachine\OrderTransitions;
use Sylius\Component\Payment\PaymentTransitions;
use Sylius\Component\Shipping\ShipmentTransitions;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class OrderCRUDController extends CRUDController
{
    /**
     * @param Request $request
     * @param mixed   $object
     *
     * @return Response|null
     */
    protected function preCreate(Request $request, $object)
    {
        // throw new AccessDeniedException();
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
        if ($object->getState() != PaymentInterface::STATE_NEW) {
            throw new AccessDeniedException('An order cannot be deleted after the checkout is completed. You should cancel it instead.');
        }
    }

    public function duplicateAction()
    {
        $id = $this->getRequest()->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id); //clone $this->admin->getObject($id);

        $preResponse = $this->preDuplicate($object);
        if ($preResponse !== null) {
            return $preResponse;
        }
        throw new AccessDeniedException('we should never be here');
        return $this->createAction(null);
    }

    /**
     * @param mixed $object
     *
     * @return Response|null
     */
    protected function preDuplicate($object)
    {
        // dump($object);
        // dump((new \ReflectionClass($object))->getMethods());
        //  die("DiE!");

        $newOrder = $this->container->get('librinfo_ecommerce.order_creation_manager')->duplicateOrder($object);
        $this->container->get('librinfo_ecommerce.order_creation_manager')->saveOrder($newOrder);
        // return $this->showAction($newOrder); /* Why show action does not work ? */
        return new RedirectResponse(
            $this->admin->generateUrl('show', ['id' => $newOrder->getId()])
        );
    }

    public function updateShippingAction(Request $request)
    {
        $modelManager = $this->admin->getModelManager();

        $orderId = $request->get('id');
        $shipmentId = $request->get('_shipmentId');
        $action = $request->get('_action');
        $tracking = $request->get('_tracking');

        /* @todo: move this to a OrderManagerService */
        $shipment = $modelManager->find($this->container->getParameter('sylius.model.shipment.class'), $shipmentId);
        // $order = $modelManager->find($this->admin->getClass(), $orderId);

        $stateMachineFactory = $this->container->get('sm.factory');

        $stateMachineShipment = $stateMachineFactory->get($shipment, ShipmentTransitions::GRAPH);
        $stateMachineShipment->apply($action);

        $shipment->setTracking($tracking);

        $this->container->get('sylius.manager.shipment')->flush();

        return new RedirectResponse(
            $this->admin->generateUrl('show', ['id' => $orderId])
        );
    }

    public function updatePaymentAction(Request $request)
    {
        $modelManager = $this->admin->getModelManager();

        $orderId = $request->get('id');
        $paymentId = $request->get('_paymentId');
        $action = $request->get('_action');

        /* @todo: move this to a OrderManagerService */
        $payment = $modelManager->find($this->container->getParameter('sylius.model.payment.class'), $paymentId);
        $order = $modelManager->find($this->admin->getClass(), $orderId);

        $stateMachineFactory = $this->container->get('sm.factory');

        $stateMachinePayment = $stateMachineFactory->get($payment, PaymentTransitions::GRAPH);
        $stateMachinePayment->apply($action);

        $this->container->get('sylius.manager.payment')->flush();

        return new RedirectResponse(
            $this->admin->generateUrl('show', ['id' => $orderId])
        );
    }

    public function cancelOrderAction($id, Request $request = null)
    {
        return $this->validateOrCancelOrders(OrderTransitions::TRANSITION_CANCEL, [$id]);
    }

    public function validateOrderAction($id, Request $request = null)
    {
        return $this->validateOrCancelOrders(OrderTransitions::TRANSITION_FULFILL, [$id]);
    }

    public function batchActionCancel(ProxyQueryInterface $selectedModelQuery, Request $request = null)
    {
        return $this->validateOrCancelOrders(OrderTransitions::TRANSITION_CANCEL, $request->request->get('idx'));
    }

    public function batchActionValidate(ProxyQueryInterface $selectedModelQuery, Request $request = null)
    {
        return $this->validateOrCancelOrders(OrderTransitions::TRANSITION_FULFILL, $request->request->get('idx'));
    }

    public function confirmOrderAction($id, Request $request = null)
    {
        return $this->validateOrCancelOrders(OrderTransitions::TRANSITION_CONFIRM, [$id]);
    }

    protected function validateOrCancelOrders($action, $targets)
    {
        $modelManager = $this->admin->getModelManager();

        $stateMachineFactory = $this->container->get('sm.factory');

        $successes = 0;

        foreach ($targets as $target) {
            $selectedModel = $modelManager->find($this->admin->getClass(), $target);

            if ($selectedModel === null) {
                $this->addFlash('sonata_flash_info', 'flash_batch_cancel_or_validate_no_target');

                return new RedirectResponse(
                    $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
                );
            }

            // try {
            /* @todo : state Machine manipulation should be done in service */
            if ($action === OrderTransitions::TRANSITION_FULFILL || $action === OrderTransitions::TRANSITION_CONFIRM) {
                $this->container
                    ->get('librinfo_ecommerce.order_creation_manager')
                    ->assignNumber($selectedModel);

                $this->container
                    ->get('librinfo_ecommerce.order_creation_manager')
                    ->initNewPayment($selectedModel);
            }
            $stateMachine = $stateMachineFactory->get($selectedModel, OrderTransitions::GRAPH);
            $stateMachine->apply($action);
            $this->container->get('sylius.manager.order')->flush();
            ++$successes;
            // } catch (\Exception $e) {
            //     $this->addFlash('sonata_flash_error', $e->getMessage());
            // }
        }

        if ($successes > 0) {
            $this->addFlash('sonata_flash_success', 'flash_batch_cancel_or_validate_success');
        }

        return new RedirectResponse(
            $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
        );
    }

    /**
     * Redirect the user depend on this choice.
     *
     * @param object $object
     *
     * @return RedirectResponse
     */
    protected function redirectTo($object)
    {
        $request = $this->getRequest();
        if ($request->get('btn_create_and_show')) {
            return new RedirectResponse($this->admin->generateObjectUrl('show', $object));
        } else {
            return parent::redirectTo($object);
        }
    }
}
