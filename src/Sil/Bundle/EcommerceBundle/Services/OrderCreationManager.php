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

namespace Librinfo\EcommerceBundle\Services;

use Doctrine\ORM\EntityManager;
use SM\Factory\Factory as SMFactory;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Core\OrderPaymentTransitions;
use Sylius\Component\Core\OrderShippingStates;
use Sylius\Component\Core\OrderShippingTransitions;
use Librinfo\EcommerceBundle\Entity\OrderInterface;
use Librinfo\EcommerceBundle\Modifier\LimitingOrderItemQuantityModifier;
use Sylius\Component\Payment\PaymentTransitions;
use Sylius\Component\Shipping\ShipmentTransitions;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\OrderRepository;
use Sylius\Bundle\OrderBundle\NumberAssigner\OrderNumberAssignerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class OrderCreationManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var SMFactory
     */
    private $stateMachineFactory;

    /**
     * @var FactoryInterface
     */
    private $orderItemFactory;

    /**
     * @var LimitingOrderItemQuantityModifier
     */
    private $orderItemQuantityModifier;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var EntityManager
     */
    private $orderManager;

    /**
     * @var OrderNumberAssignerInterface
     */
    private $orderNumberAssigner;

    /**
     * @var FactoryInterface
     */
    private $orderFactory;

    /**
     * @var FactoryInterface
     */
    private $addressFactory;

    /**
     * @var FactoryInterface
     */
    private $customerFactory;

    public function copyAddress(OrderInterface $order, array $data, string $key = 'shippingAddress')
    {
        $orderAddress = null;
        switch ($key) {
            case 'shippingAddress':
                $orderAddress = $order->getShippingAddress();
                break;
            case 'billingAddress':
                $orderAddress = $order->getBillingAddress();
                break;
            case 'customerAddress':
                /* Thanks to Not Typed Php Language ! Should not be done */
                $orderAddress = $order->getCustomer();
                $key = 'shippingAddress'; /* Ugly hack ! Should use another variable as key */
                break;
        }

        if (isset($orderAddress)) {
            foreach ($data[$key] as $field => $bData) {
                try {
                    $propertyAccessor = new PropertyAccessor();
                    $propertyAccessor->setValue($orderAddress, $field, $bData);
                } catch (NoSuchPropertyException $e) {
                    /* Ah Ah Not Always The Same Object ! */
                }
            }
        }

        return $orderAddress;
    }

    /**
     * @param OrderInterface oldOrder
     */
    public function initNewShipment(OrderInterface $order)
    {
        foreach ($order->getShipments() as $oShipment) {
            // Ugly hack : we should not set state before stateMachine and we should not use OrderShippingStates as ShippingStates
            $oShipment->setState(OrderShippingStates::STATE_CART);
            $stateMachine = $this->stateMachineFactory->get($oShipment, ShipmentTransitions::GRAPH);
            $stateMachine->apply(ShipmentTransitions::TRANSITION_CREATE);
        }
    }

    /**
     * @param OrderInterface oldOrder
     */
    public function initNewPayment(OrderInterface $order)
    {
        foreach ($order->getPayments() as $oPayment) {
            $oPayment->setState(OrderPaymentStates::STATE_CART);
            $stateMachine = $this->stateMachineFactory->get($oPayment, PaymentTransitions::GRAPH);
            $stateMachine->apply(PaymentTransitions::TRANSITION_CREATE);
        }
    }

    /**
     * @param OrderInterface oldOrder
     * @param OrderInterface newOrder
     */
    public function copyShipment(OrderInterface $oldOrder, OrderInterface $newOrder)
    {
        foreach ($oldOrder->getShipments() as $oShipment) {
            $newOrder->addShipment(clone $oShipment);
        }
        $this->initNewShipment($newOrder);
    }

    /**
     * @param OrderInterface oldOrder
     * @param OrderInterface newOrder
     */
    public function copyPayment(OrderInterface $oldOrder, OrderInterface $newOrder)
    {
        foreach ($oldOrder->getPayments() as $oPayment) {
            $newOrder->addPayment(clone $oPayment);
        }
        $this->initNewPayment($newOrder);
    }

    /**
     * @param OrderInterface oldOrder
     * @param OrderInterface newOrder
     */
    public function copyOrderItem(OrderInterface $oldOrder, OrderInterface $newOrder)
    {
        foreach ($oldOrder->getItems() as $oItem) {
            $newItem = $this->orderItemFactory->createNew(); // clone $oItem;
            $newItem->setBulk($oItem->isBulk());
            $newItem->setVariant($oItem->getVariant());
            $newItem->setUnitPrice($oItem->getUnitPrice());

            $this->orderItemQuantityModifier->modify($newItem, $oItem->getQuantity());

            $newItem->setQuantity($oItem->getQuantity());

            foreach ($oItem->getAdjustments() as $oItemAdjust) {
                $newItem->addAdjustment($oItemAdjust);
            }
            $newOrder->addItem($newItem);
        }
    }

    /**
     * @param OrderInterface oldOrder
     *
     * @return OrderInterface
     */
    public function duplicateOrder(OrderInterface $oldOrder)
    {
        $newOrder = $this->createOrder();

        $newOrder->setChannel($oldOrder->getChannel());
        $newOrder->setCustomer($oldOrder->getCustomer());
        $newOrder->setCurrencyCode($oldOrder->getCurrencyCode());
        $newOrder->setLocaleCode($oldOrder->getLocaleCode());
        $newOrder->setBillingAddress(clone $oldOrder->getBillingAddress());
        $newOrder->setShippingAddress(clone $oldOrder->getShippingAddress());

        $this->copyShipment($oldOrder, $newOrder);
        $this->copyPayment($oldOrder, $newOrder);

        foreach ($oldOrder->getPromotions() as $oPro) {
            $newOrder->addPromotion(clone $oPro);
        }

        foreach ($oldOrder->getAdjustments() as $oAdjust) {
            $newOrder->addAdjustment(clone $oAdjust);
        }

        $this->copyOrderItem($oldOrder, $newOrder);

        return $newOrder;
    }

    public function saveOrder(OrderInterface $newOrder)
    {
        $this->assignNumber($newOrder); /* in case it have not been done, but it should never hapen :) */

        /* http://docs.sylius.org/en/latest/book/orders/orders.html */
        // Warning: process reset shipment if there is no item in the list
        // $this->container->get('sylius.order_processing.order_processor')->process($newOrder);

        $this->orderRepository->add($newOrder);
        $this->orderManager->flush($newOrder);

        return true;
    }

    public function assignNumber(OrderInterface $order)
    {
        $this->orderNumberAssigner->assignNumber($order);
    }

    /**
     * @return OrderInterface
     */
    public function createOrder()
    {
        $newOrder = $this->orderFactory->createNew();
        $addressFactory = $this->addressFactory;
        $customerFactory = $this->customerFactory;

        $newOrder->setShippingAddress($addressFactory->createNew());
        $newOrder->setBillingAddress($addressFactory->createNew());
        $newOrder->setCustomer($customerFactory->createNew());

        $this->assignNumber($newOrder);
        $newOrder->setCheckoutCompletedAt(new \DateTime('NOW'));
        $newOrder->setState(OrderInterface::STATE_DRAFT);
        $newOrder->setPaymentState(OrderPaymentStates::STATE_CART);
        $newOrder->setShippingState(OrderShippingStates::STATE_CART);

        $stateMachine = $this->stateMachineFactory->get($newOrder, OrderShippingTransitions::GRAPH);
        $stateMachine->apply(OrderShippingTransitions::TRANSITION_REQUEST_SHIPPING);

        $stateMachine = $this->stateMachineFactory->get($newOrder, OrderPaymentTransitions::GRAPH);
        $stateMachine->apply(OrderPaymentTransitions::TRANSITION_REQUEST_PAYMENT);

        return $newOrder;
    }

    /**
     * @param EntityManager $em
     */
    public function setEm(EntityManager $em): void
    {
        $this->em = $em;
    }

    /**
     * @param SMFactory $stateMachineFactory
     */
    public function setStateMachineFactory(SMFactory $stateMachineFactory): void
    {
        $this->stateMachineFactory = $stateMachineFactory;
    }

    /**
     * @param FactoryInterface $orderItemFactory
     */
    public function setOrderItemFactory(FactoryInterface $orderItemFactory): void
    {
        $this->orderItemFactory = $orderItemFactory;
    }

    /**
     * @param LimitingOrderItemQuantityModifier $orderItemQuantityModifier
     */
    public function setOrderItemQuantityModifier(LimitingOrderItemQuantityModifier $orderItemQuantityModifier): void
    {
        $this->orderItemQuantityModifier = $orderItemQuantityModifier;
    }

    /**
     * @param OrderRepository $orderRepository
     */
    public function setOrderRepository(OrderRepository $orderRepository): void
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param EntityManager $orderManager
     */
    public function setOrderManager(EntityManager $orderManager): void
    {
        $this->orderManager = $orderManager;
    }

    /**
     * @param OrderNumberAssignerInterface $orderNumberAssigner
     */
    public function setOrderNumberAssigner(OrderNumberAssignerInterface $orderNumberAssigner): void
    {
        $this->orderNumberAssigner = $orderNumberAssigner;
    }

    /**
     * @param FactoryInterface $orderFactory
     */
    public function setOrderFactory(FactoryInterface $orderFactory): void
    {
        $this->orderFactory = $orderFactory;
    }

    /**
     * @param FactoryInterface $addressFactory
     */
    public function setAddressFactory(FactoryInterface $addressFactory): void
    {
        $this->addressFactory = $addressFactory;
    }

    /**
     * @param FactoryInterface $customerFactory
     */
    public function setCustomerFactory(FactoryInterface $customerFactory): void
    {
        $this->customerFactory = $customerFactory;
    }
}
