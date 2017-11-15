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

use Sil\Bundle\StockBundle\Admin\OperationAdmin as BaseStockOperationAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sil\Bundle\StockBundle\Domain\Entity\OperationType;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class StockOperationAdmin extends BaseStockOperationAdmin
{
    /**
     * @param type          $group
     * @param OperationType $type
     * @param FormMapper    $mapper
     */
    protected function createPartnerField($group, OperationType $type,
        FormMapper $mapper)
    {
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository('SilCRMBundle:Organism');

        if ($type->isReceipt()) {
            $parters = $repository->findBy(['isSupplier' => true]);
        } else {
            $parters = $repository->findAll();
        }

        $group->add('partner', 'choice',
            [
                'choices'      => $parters,
                'choice_label' => 'fulltextName',
            ], ['admin_code' => 'sil_seed_batch.admin.organism']
        );
    }

    public function prePersist($operation)
    {
        parent::prePersist($operation);
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        parent::preUpdate($object);
    }
}
