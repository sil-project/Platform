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

use Sonata\AdminBundle\Route\RouteCollection;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Core\Model\PaymentInterface;

class PaymentAdmin extends SyliusGenericAdmin
{
    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.ecommerce.payment';

    protected $baseRouteName = 'admin_sil_ecommerce_payment';
    protected $baseRoutePattern = 'ecommerce/payment';
    protected $classnameLabel = 'Payment';

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $alias = $query->getRootAliases()[0];

        $query
            ->join("$alias.order", 'ord')
            ->where(
                $query->expr()->in(
                    'ord.paymentState',
                    [OrderPaymentStates::STATE_PAID, OrderPaymentStates::STATE_REFUNDED, OrderPaymentStates::STATE_AWAITING_PAYMENT]
                )
            )->andWhere(
                $query->expr()->not(
                    $query->expr()->in(
                        "$alias.state",
                        [PaymentInterface::STATE_CART]
                    )
                )
            );

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function configureActionButtons($action, $object = null)
    {
        $list = parent::configureActionButtons($action, $object);

        if (isset($list['create'])) {
            unset($list['create']);
        }

        return $list;
    }

    public function toString($object)
    {
        return $object->getOrder()->getNumber() ?: $object->getId();
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list', 'show', 'export'));
    }

    public function getDataSourceIterator()
    {
        $iterator = parent::getDataSourceIterator();
        $iterator->setDateTimeFormat('d/m/Y H:i:s');

        return $iterator;
    }

    public function getExportFields()
    {
        return [
            'order.number'                                              => 'order.number',
            $this->trans('sil.ecommerce.dashboard.label.invoice_number')          => 'order.getLastDebitInvoice.number',
            'order.customer'                                            => 'order.customer',
            'order.channel'                                             => 'order.channel',
            'method'                                                    => 'method',
            'order.paymentState'                                        => 'order.paymentState',
            'amount'                                                    => 'amount',
            'createdAt'                                                 => 'createdAt',
            'updatedAt'                                                 => 'updatedAt',
        ];
    }
}
