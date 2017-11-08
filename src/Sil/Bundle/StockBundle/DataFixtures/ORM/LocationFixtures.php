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
use Sil\Bundle\StockBundle\Domain\Entity\Location;
use Sil\Bundle\StockBundle\Domain\Entity\LocationType;

/**
 * Description of WarehouseFixtures.
 *
 * @author glenn
 */
class LocationFixtures extends Fixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function load(ObjectManager $manager)
    {
        $wh = $this->getReference('wh-1');

        $supp = Location::createDefault('SUPPLIER', 'Emplacement Fournisseur',
                LocationType::supplier());

        $supp1 = Location::createDefault('SUP-1', 'Fournisseur 1',
                LocationType::supplier());

        $supp2 = Location::createDefault('SUP-2', 'Fournisseur 2',
                LocationType::supplier());

        $supp->addChild($supp1);
        $supp->addChild($supp2);

        $wh->addLocation($supp);

        $int = Location::createDefault('INTERNAL', 'Emplacement Stock',
                LocationType::internal());

        $int1 = Location::createDefault('STOCK-1', 'Stock 1',
                LocationType::internal());

        $int2 = Location::createDefault('STOCK-2', 'Stock 2',
                LocationType::internal());

        $int->addChild($int1);
        $int->addChild($int2);
        $wh->addLocation($int);

        $inv = Location::createDefault('INV', 'Ajustement de stock',
                LocationType::virtual());

        $crap = Location::createDefault('SCRAP', 'Emplacement de rebut',
                LocationType::scrap());

        $wh->addLocation($inv);
        $wh->addLocation($crap);

        $manager->persist($supp);
        $manager->persist($int);
        $manager->persist($inv);
        $manager->persist($crap);
        $manager->flush();

        // other fixtures can get this object using the 'admin-user' name
        $this->addReference('supplier-1', $supp1);
        $this->addReference('stock-1', $int2);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getDependencies()
    {
        return array(
            WarehouseFixtures::class,
        );
    }
}
