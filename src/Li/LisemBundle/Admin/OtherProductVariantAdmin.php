<?php

/*
 * This file is part of the Lisem Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\Admin;

use Sil\Bundle\EcommerceBundle\Admin\ProductVariantAdmin;

/**
 * Sonata admin for product variants from non-seed products.
 *
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class OtherProductVariantAdmin extends ProductVariantAdmin
{
    protected $baseRouteName = 'admin_sil_other_productvariant';
    protected $baseRoutePattern = 'sil/other_productvariant';
    protected $classnameLabel = 'OtherProductVariant';

    protected $productAdminCode = 'lisem.admin.other_product';

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $alias = $query->getRootAliases()[0];
        $query->leftJoin("$alias.product", 'prod');
        $query->andWhere(
            $query->expr()->isNull('prod.variety')
        );

        return $query;
    }
}
