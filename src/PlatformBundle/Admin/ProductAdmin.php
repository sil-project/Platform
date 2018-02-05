<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PlatformBundle\Admin;

use Sil\Bundle\EcommerceBundle\Admin\ProductAdmin as BaseProductAdmin;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ProductAdmin extends BaseProductAdmin
{
    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.ecommerce.product';

    protected $baseRouteName = 'admin_ecommerce_product';
    protected $baseRoutePattern = 'product';

    public function toString($item)
    {
        return sprintf('[%s] %s', $item->getCode(), $item->getName());
    }
}
