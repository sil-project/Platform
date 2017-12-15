<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Admin;

use Blast\Bundle\CoreBundle\Admin\Traits\EmbeddedAdmin;

class ProductVariantEmbeddedAdmin extends ProductVariantAdmin
{
    use EmbeddedAdmin;

    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.ecommerce.product_variant';

    protected $baseRouteName = 'admin_ecommerce_product_variant';
    protected $baseRoutePattern = 'ecommerce/product_variant';
}
