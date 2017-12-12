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

use Sil\Bundle\CRMBundle\Admin\CustomerAdmin as BaseCustomerAdmin;

class CustomerAdmin extends BaseCustomerAdmin
{
    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.ecommerce.customer';

    protected $baseRouteName = 'admin_ecommerce_customer';
    protected $baseRoutePattern = 'ecommerce/customer';

    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        $object->updateName();
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        $object->updateName();
    }
}
