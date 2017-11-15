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

use Sil\Bundle\EcommerceBundle\Entity\Product;
use Sil\Bundle\VarietyBundle\Admin\VarietyAdmin as BaseAdmin;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

/**
 * Lisem Sonata admin for varieties.
 *
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class VarietyAdmin extends BaseAdmin
{
    protected $baseRouteName = 'admin_sil_variety';
    protected $baseRoutePattern = 'sil/variety';

    public function configureFormFields(\Sonata\AdminBundle\Form\FormMapper $mapper)
    {
        parent::configureFormFields($mapper);
        $mapper
            ->tab('form_tab_general')
                ->with('form_group_identification')
                    ->add(
                        'packagings',
                        'sonata_type_model',
                        [
                        'multiple' => true,
                        'required' => false,
                        'query'    => $this->packagingQueryBuilder(),
                        ],
                        ['admin_code' => 'lisem.admin.packaging']
                    )
                ->end()
            ->end();
    }

    /**
     * Configure routes for list actions.
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
    }

    protected function packagingQueryBuilder()
    {
        $repository = $this->getConfigurationPool()->getContainer()->get('sylius.repository.product_option_value');
        $queryBuilder = $repository->createQueryBuilder('pov')
            ->leftJoin('pov.option', 'po')
            ->andWhere('po.code = :code')
            ->setParameter('code', Product::$PACKAGING_OPTION_CODE)
        ;

        return $queryBuilder->getQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function preBatchAction($actionName, ProxyQueryInterface $query, array &$idx, $allElements)
    {
        parent::preBatchAction($actionName, $query, $idx, $allElements);
        // if ($actionName === 'delete') {
        //     $varieties = $this->getModelManager()->findBy($this->getClass(), ['id' => $idx]);
        //
        //     foreach ($varieties as $variety) {
        //         // Check products
        //         if ($variety->getProducts()->count() > 0) {
        //             $errorMessage = sprintf('Cannot delete variety « %s » because it has product', $variety->getName());
        //             $this->getFlashManager()->addMessage('warning', $errorMessage);
        //             unset($idx[array_search($variety->getId(), $idx)]);
        //         }
        //
        //         if ($variety->getSeedBatches()->count() > 0) {
        //             $errorMessage = sprintf('Cannot delete variety « %s » because it has seed batches', $variety->getName());
        //             $this->getFlashManager()->addMessage('warning', $errorMessage);
        //             unset($idx[array_search($variety->getId(), $idx)]);
        //         }
        //     }
        // }
    }
}
