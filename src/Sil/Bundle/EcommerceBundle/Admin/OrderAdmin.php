<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Admin;

use Blast\Bundle\CoreBundle\Admin\CoreAdmin;
use Sil\Bundle\EcommerceBundle\Form\Type\OrderAddressType;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sil\Bundle\EcommerceBundle\Entity\OrderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Bundle\PaymentBundle\Form\Type\PaymentMethodChoiceType;
use Sylius\Bundle\ShippingBundle\Form\Type\ShippingMethodChoiceType;

class OrderAdmin extends CoreAdmin
{
    /* @todo : remove this useless protected attributes */

    protected $baseRouteName = 'admin_sil_ecommerce_order';
    protected $baseRoutePattern = 'sil/ecommerce/order';
    protected $classnameLabel = 'Order';
    protected $datagridValues = [
        '_page'       => 1,
        '_sort_order' => 'DESC',
        '_sort_by'    => 'createdAt',
    ];

    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->clearExcept(array('list', 'show', 'batch', 'create', 'duplicate'));
        $collection->add('updateShipping', $this->getRouterIdParameter() . '/updateShipping');
        $collection->add('updatePayment', $this->getRouterIdParameter() . '/updatePayment');
        $collection->add('cancelOrder', $this->getRouterIdParameter() . '/cancelOrder');
        $collection->add('validateOrder', $this->getRouterIdParameter() . '/validateOrder');
        $collection->add('confirmOrder', $this->getRouterIdParameter() . '/confirmOrder');
    }

    public function configureBatchActions($actions)
    {
        $actions = parent::configureBatchActions($actions);

        $actions['cancel'] = [
            'ask_confirmation' => true,
            'label'            => 'sil.label.cancel_order',
        ];

        $actions['validate'] = [
            'ask_confirmation' => true,
            'label'            => 'sil.label.fulfill_order',
        ];

        return $actions;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $alias = $query->getRootAliases()[0];
        $query
            ->addSelect('channel')
            ->leftJoin("$alias.channel", 'channel')
            ->addSelect('customer')
            ->leftJoin("$alias.customer", 'customer')
            ->andWhere("$alias.state != :state")
            ->setParameter('state', OrderInterface::STATE_CART);

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function configureActionButtons($action, $object = null)
    {
        $list = parent::configureActionButtons($action, $object);

        return $list;
    }

    public function toString($object)
    {
        return $object->getNumber() ?: $object->getId();
    }

    protected function configureFormFields(FormMapper $mapper)
    {
        parent::configureFormFields($mapper);

        $mapper
            ->tab('form_tab_general')
                ->with('form_group_general')
                    ->add('locale_code', HiddenType::class, [
                        'data' => $this->getConfigurationPool()->getContainer()
                        ->getParameter('locale'),
                    ])
                ->end()
            ->end()
        ;
        $admin = $this;
        $mapper->getFormBuilder()
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($admin) {
                $data = $event->getData();
                $form = $event->getForm();

                $orderCreationTools = $this->getConfigurationPool()->getContainer()
                    ->get('sil_ecommerce.order_creation_manager');
                $order = $admin->getSubject();

                if (isset($data['channel'])) {
                    $channel = $this->getConfigurationPool()->getContainer()
                             ->get('sylius.repository.channel')->find($data['channel']);
                    $order->setChannel($channel);

                    // Handle payment methods values from selected channel

                    $form->remove('payment');
                    $paymentMethodRepo = $this->getConfigurationPool()->getContainer()
                             ->get('sylius.repository.payment_method');

                    $availablePaymentMethods = $paymentMethodRepo->createQueryBuilder('o')
                        ->leftJoin('o.channels', 'c')
                        ->where('c = :channel')
                        ->andWhere('o.enabled = true')
                        ->setParameter('channel', $channel)
                        ->getQuery()->getResult();

                    $form->add('payment', PaymentMethodChoiceType::class, [
                        'label'    => 'sil.label.payment_method',
                        'multiple' => false,
                        'mapped'   => false,
                        'required' => true,
                        'choices'  => $availablePaymentMethods,
                    ]);

                    // Handle shipping methods values from selected channel

                    $form->remove('shipment');
                    $shippingMethodRepo = $this->getConfigurationPool()->getContainer()
                             ->get('sylius.repository.shipping_method');

                    $availableShippingMethods = $shippingMethodRepo->createQueryBuilder('o')
                        ->leftJoin('o.channels', 'c')
                        ->where('c = :channel')
                        ->andWhere('o.enabled = true')
                        ->setParameter('channel', $channel)
                        ->getQuery()->getResult();

                    $form->add('shipment', ShippingMethodChoiceType::class, [
                        'label'    => 'sil.label.shipping_method',
                        'multiple' => false,
                        'mapped'   => false,
                        'required' => true,
                        'choices'  => $availableShippingMethods,
                    ]);
                }

                if (isset($data['shippingAddress']) && isset($data['shippingAddress']['email'])) {
                    $orderCreationTools->copyAddress($order, $data, 'shippingAddress');
                    $customer = $order->getCustomer();
                    $customer->setEmail($data['shippingAddress']['email']);
                    $customer->setEmailCanonical($data['shippingAddress']['email']);
                    //$customer->setUsernameCanonical($customer->getName());
                    $orderCreationTools->copyAddress($order, $data, 'customerAddress');
                }

                if (isset($data['shippingAddress']) && isset($data['shippingAddress']['useSameAddressForBilling'])) {
                    if ((bool) $data['shippingAddress']['useSameAddressForBilling'] === true) {
                        // Allow empty sub form submit
                        $form->remove('billingAddress');
                        $form->add('billingAddress', OrderAddressType::class, [
                            'label'       => false,
                            'data_class'  => $this->getConfigurationPool()->getContainer()->getParameter('sylius.model.address.class'),
                            'mapped'      => false,
                            'constraints' => [],
                            'attr'        => [
                                'class' => 'nested-form',
                            ],
                            'validation_groups' => false,
                        ]);

                        $data['billingAddress'] = $data['shippingAddress'];
                        unset($data['billingAddress']['useSameAddressForBilling']);
                        $orderCreationTools->copyAddress($order, $data, 'billingAddress');

                        /* ? useless as we already get the referenced object in getBillingAddress ? */
                        // $order->setBillingAddress($billingData);
                    }
                }

                /* @todo: add a tools to set payment from form in 'sil_ecommerce.order_creation_manager' */
                if (isset($data['payment'])) {
                    $paymentCode = $data['payment'];

                    $payment = $this->getConfigurationPool()->getContainer()
                             ->get('sylius.factory.payment')->createNew();

                    $currency = $this->getConfigurationPool()->getContainer()
                              ->get('sylius.repository.currency')->find($data['currency_code']);

                    $paymentMethod = $this->getConfigurationPool()->getContainer()
                        ->get('sylius.repository.payment_method')
                        ->findOneBy(['code' => $paymentCode]);

                    $payment->setMethod($paymentMethod);
                    $payment->setCurrencyCode($currency->getCode());
                    $payment->setState(PaymentInterface::STATE_NEW);

                    $order->addPayment($payment);
                }

                /* @todo: add a tools to set shipment from form in 'sil_ecommerce.order_creation_manager' */
                if (isset($data['shipment'])) {
                    $shipmentCode = $data['shipment'];

                    $shipment = $this->getConfigurationPool()->getContainer()
                        ->get('sylius.factory.shipment')->createNew();

                    $shipmentMethod = $this->getConfigurationPool()->getContainer()
                        ->get('sylius.repository.shipping_method')
                        ->findOneBy(['code' => $shipmentCode]);

                    $shipment->setMethod($shipmentMethod);
                    $shipment->setState(ShipmentInterface::STATE_READY);

                    $order->addShipment($shipment);
                }

                $form->setData($order);
                $event->setData($data); /* useless ? */
            });
    }

    public function getNewInstance()
    {
        // 1
        $order = $this->getConfigurationPool()->getContainer()
            ->get('sil_ecommerce.order_creation_manager')
            ->createOrder();

        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        // 3
        /** @var Sil\Bundle\EcommerceBundle\Entity\Order $order */
        $order = $object;

        // Hum why call Parent ?
        parent::prePersist($order);

        $this->getConfigurationPool()->getContainer()
            ->get('sil_ecommerce.order_customer_manager')
            ->associateUserAndAddress($order);

        $this->getConfigurationPool()->getContainer()
            ->get('sil_ecommerce.order_creation_manager')
            ->saveOrder($order);
    }
}
