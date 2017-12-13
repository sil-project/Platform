<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\UomBundle\DataFixtures\ORM;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sil\Bundle\UomBundle\Entity\UomType;
use Sil\Bundle\UomBundle\Entity\Uom;

/**
 * Description of WarehouseFixtures.
 *
 * @author glenn
 */
class UomFixtures extends Fixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function load(ObjectManager $manager)
    {
        $uomTypeMass = $this->createUomType('Mass');
        $this->addReference('uom-mass-type', $uomTypeMass);
        $this->addReference('uom-T', $this->createUom($uomTypeMass, 'T', 0.001));
        $this->addReference('uom-Kg', $this->createUom($uomTypeMass, 'Kg', 1));
        $this->addReference('uom-g', $this->createUom($uomTypeMass, 'g', 1000));
        $this->addReference('uom-mg', $this->createUom($uomTypeMass, 'mg', 1000000));

        $uomTypeLength = $this->createUomType('Length');
        $this->addReference('uom-length-type', $uomTypeLength);
        $this->addReference('uom-Km', $this->createUom($uomTypeLength, 'Km', 0.001));
        $this->addReference('uom-m', $this->createUom($uomTypeLength, 'm', 1));
        $this->addReference('uom-cm', $this->createUom($uomTypeLength, 'cm', 100));
        $this->addReference('uom-mm', $this->createUom($uomTypeLength, 'mm', 1000));

        $manager->flush();
    }

    public function createUom(UomType $type, string $name, float $factor)
    {
        $uom = Uom::createDefault($type, $name, $factor);
        $this->getUomRepository()->add($uom);

        return $uom;
    }

    public function createUomType($name)
    {
        $uomType = UomType::createDefault($name);
        $this->getUomTypeRepository()->add($uomType);

        return $uomType;
    }

    public function getUomRepository()
    {
        return $this->container->get('sil.uom.repository');
    }

    public function getUomTypeRepository()
    {
        return $this->container->get('sil.uom_type.repository');
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
