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
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Datagrid\ListMapper;

class SalesJournalAdmin extends CoreAdmin
{
    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.ecommerce.sales_journal';

    protected $baseRouteName = 'admin_sil_ecommerce_sales_journal';
    protected $baseRoutePattern = 'ecommerce/sales_journal';
    protected $classnameLabel = 'SalesJournal';

    protected $datagridValues = array(
        '_page'       => 1,
        '_per_page'   => 192,
        '_sort_order' => 'DESC',
        '_sort_by'    => 'operationDate',
    );

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'show', 'export']);
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

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions['delete']);

        return $actions;
    }

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list)
    {
        parent::configureListFields($list);

        $list->remove('batch');
    }

    public function toString($object)
    {
        return $object->getNumber() ?: $object->getId();
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
            'order.number'   => 'order.number',
            'invoice.number' => 'invoice.number',
            'payment'        => 'payment',
            'label'          => 'label',
            'debit'          => 'debit',
            'credit'         => 'credit',
            'operation_date' => 'operationDate',
        ];
    }
}
