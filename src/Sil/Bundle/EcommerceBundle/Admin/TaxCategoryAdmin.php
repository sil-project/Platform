<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Admin;

class TaxCategoryAdmin extends SyliusGenericAdmin
{
    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.ecommerce.tax_category';

    protected $baseRouteName = 'admin_ecommerce_tax_category';
    protected $baseRoutePattern = 'ecommerce/tax_category';

    public function toString($object)
    {
        /* (As we don't have name here for title) */
        return $object->getcode() ?: $object->getId();
    }
}
