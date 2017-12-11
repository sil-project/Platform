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

// use Blast\Bundle\CoreBundle\Admin\CoreAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class ShopUserAdmin extends SyliusGenericAdmin
{
    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.ecommerce.shop_user';

    protected $baseRouteName = 'admin_sil_ecommerce_shop_user';
    protected $baseRoutePattern = 'ecommerce/shop_user';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list', 'show', 'edit'));
    }
}
