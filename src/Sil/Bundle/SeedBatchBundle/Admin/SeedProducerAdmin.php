<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\SeedBatchBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;

class SeedProducerAdmin extends OrganismAdmin
{
    protected $baseRouteName = 'admin_sil_seedbatch_seedProducer';
    protected $baseRoutePattern = 'sil/seedbatch/seed-producer';

    /**
     * The label class name  (used in the title/breadcrumb ...).
     *
     * @var string
     */
    protected $classnameLabel = 'SeedProducer';

    /**
     * {@inheritdoc}
     */
    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $query->andWhere('o.seedProducer = true');

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewInstance()
    {
        $object = parent::getNewInstance();
        $seedProducersCircle = $this->getConfigurationPool()->getContainer()->get('sil_crm.app_circles')->getCircle('seed_producers');

        $object->setSeedProducer(true);
        $object->addCircle($seedProducersCircle);

        return $object;
    }

    /**
     * This is used as callback in admin autocomplete producer (organism) fields
     * It restricts the query to seed producers.
     *
     * @param AbstractAdmin $admin
     * @param string|array  $property
     * @param string        $value
     */
    public static function producerAutocompleteCallback(AbstractAdmin $admin, $property, $value)
    {
        $datagrid = $admin->getDatagrid();
        $qb = $datagrid->getQuery();

        $searchIndex = $admin->getClass() . 'SearchIndex';

        $qb
            ->leftJoin($searchIndex, 's', 'WITH', $qb->getRootAlias() . '.id = s.object')
            ->where('s.keyword LIKE :value')
            ->setParameter('value', "%$value%");
    }
}
