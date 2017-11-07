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

namespace Librinfo\EcommerceBundle\Admin;

use Sonata\AdminBundle\Route\RouteCollection;

class PaymentMethodAdmin extends SyliusGenericAdmin
{
    /* @todo : remove this useless protected attributes */
    protected $baseRouteName = 'admin_librinfo_ecommerce_payment_method';
    protected $baseRoutePattern = 'librinfo/ecommerce/payment_method';
    protected $classnameLabel = 'PaymentMethod';

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $alias = $query->getRootAliases()[0];

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
        return $object->getcode() ?: $object->getId();
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->remove('delete');
    }
}
