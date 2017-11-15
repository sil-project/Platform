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

use Sil\Bundle\EcommerceBundle\Admin\ProductOptionValueAdmin;
use Sil\Bundle\EcommerceBundle\Entity\Product;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class PackagingAdmin extends ProductOptionValueAdmin
{
    protected $baseRouteName = 'admin_sil_packaging';
    protected $baseRoutePattern = 'sil/packaging';
    protected $classnameLabel = 'Packaging';

    /**
     * {@inheritdoc}
     */
    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $query->leftJoin($query->getRootAlias() . '.option', 'option')
            ->andWhere('option.code = :code')
            ->setParameter('code', Product::$PACKAGING_OPTION_CODE)
        ;

        return $query;
    }

    /**
     * @param FormMapper $mapper
     *
     * @todo  handle multiple locales
     */
    public function configureFormFields(FormMapper $mapper)
    {
        parent::configureFormFields($mapper);

        $builder = $mapper->getFormBuilder();

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();

            $entity = $event->getData();
            if (isset($entity)) {
                $form->get('quantity')->setData($entity->getQuantity());
                $form->get('unit')->setData($entity->getUnit());
                $form->get('bulk')->setData($entity->isBulk());
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $bulk = !empty($data['bulk']);
            $data['code'] = $bulk ? 'BULK' :
                sprintf('%03g%s', $data['quantity'], $data['unit'] == 'seeds' ? 'S' : 'G');
            // TODO: translate this :
            $data['value'] = $bulk ? 'Vrac' :
                sprintf('%g%s', $data['quantity'], $data['unit'] == 'seeds' ? ' graines' : 'g');

            $event->setData($data);
        });
    }

    public function getNewInstance()
    {
        $entity = parent::getNewInstance();

        $repository = $this->getConfigurationPool()->getContainer()->get('sylius.repository.product_option');
        $packagingOption = $repository->findOneByCode(Product::$PACKAGING_OPTION_CODE);
        if (!$packagingOption) {
            throw new \Exception('Could not find ProductOption with code ' . Product::$PACKAGING_OPTION_CODE);
        }
        $entity->setOption($packagingOption);

        return $entity;
    }
}
