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

use Sil\Bundle\EcommerceBundle\Admin\ProductAdmin;
use Sylius\Component\Product\Model\ProductInterface;

/**
 * Sonata admin for other products (not seeds).
 *
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class OtherProductAdmin extends ProductAdmin
{
    protected $baseRouteName = 'admin_sil_other_product';
    protected $baseRoutePattern = 'sil/other_product';
    protected $classnameLabel = 'OtherProduct';

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $alias = $query->getRootAliases()[0];
        $query->andWhere(
            $query->expr()->isNull("$alias.variety")
        );

        return $query;
    }

    /**
     * @return ProductInterface
     */
    public function getNewInstance()
    {
        $factory = $this->getConfigurationPool()->getContainer()->get('sylius.factory.product');
        $object = $factory->createNew();

        foreach ($this->getExtensions() as $extension) {
            $extension->alterNewInstance($this, $object);
        }

        return $object;
    }

    public function SonataTypeModelAutocompleteCallback($admin, $property, $value)
    {
        $datagrid = $admin->getDatagrid();
        $qb = $datagrid->getQuery();
        $alias = $qb->getRootAlias();
        $qb
            ->leftJoin("$alias.translations", 'translations')
            ->andWhere($qb->expr()->orX(
                'translations.name LIKE :value',
                $alias . '.code LIKE :value'
            ))
            ->setParameter('value', "%$value%")
        ;
    }
}
