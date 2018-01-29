<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ManufacturingBundle\Admin;

use Blast\Bundle\ResourceBundle\Sonata\Admin\ResourceAdmin;
use Sil\Bundle\ManufacturingBundle\Domain\Generator\ManufacturingOrderCodeGeneratorInterface;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class ManufacturingOrderAdmin extends ResourceAdmin
{
    protected $baseRouteName = 'admin_manufacturing_order';
    protected $baseRoutePattern = 'manufacturing/order';

    /**
     * @var ManufacturingOrderCodeGeneratorInterface
     */
    private $poCodeGenerator;

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('cancel', $this->getRouterIdParameter() . '/cancel');
        $collection->add('confirm', $this->getRouterIdParameter() . '/confirm');
        $collection->add('reserve', $this->getRouterIdParameter() . '/reserve');
        $collection->add('unreserve', $this->getRouterIdParameter() . '/unreserve');
        $collection->add('apply', $this->getRouterIdParameter() . '/apply');
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($operation)
    {
        $this->preUpdate($operation);
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        //generate code for the bom
        $code = $this->getManufacturingOrderCodeGenerator()->generate();
        $object->setCode($code);

        parent::preUpdate($object);
    }

    /**
     * @return ManufacturingOrderCodeGeneratorInterface
     */
    public function getManufacturingOrderCodeGenerator(): ManufacturingOrderCodeGeneratorInterface
    {
        return $this->poCodeGenerator;
    }

    /**
     * @param ManufacturingOrderCodeGeneratorInterface $bomCodeGenerator
     */
    public function setManufacturingOrderCodeGenerator(ManufacturingOrderCodeGeneratorInterface $poCodeGenerator)
    {
        $this->poCodeGenerator = $poCodeGenerator;
    }

    public function toString($object)
    {
        return $object->getCode();
    }
}
