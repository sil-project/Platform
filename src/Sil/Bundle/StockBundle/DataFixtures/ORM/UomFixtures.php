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

namespace Sil\Bundle\StockBundle\DataFixtures\ORM;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sil\Bundle\StockBundle\Domain\Entity\UomType;
use Sil\Bundle\StockBundle\Domain\Entity\Uom;

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
        $uomTypeMass = UomType::createDefault('Mass');
        $uomTypePiece = UomType::createDefault('Piece');

        $uomT = Uom::createDefault($uomTypeMass, 'T', 0.001);
        $uomKg = Uom::createDefault($uomTypeMass, 'Kg', 1.0);
        $uomGr = Uom::createDefault($uomTypeMass, 'g', 1000);
        $uomMg = Uom::createDefault($uomTypeMass, 'mg', 1000000.0);

        $uomUnit = Uom::createDefault($uomTypePiece, 'u', 1);
        $uomUnit->setRounding(1);

        $manager->persist($uomTypeMass);
        $manager->persist($uomT);
        $manager->persist($uomKg);
        $manager->persist($uomGr);
        $manager->persist($uomMg);

        $manager->persist($uomTypePiece);
        $manager->persist($uomUnit);

        $manager->flush();
        // other fixtures can get this object using the 'admin-user' name
        $this->addReference('uom-kg', $uomKg);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
