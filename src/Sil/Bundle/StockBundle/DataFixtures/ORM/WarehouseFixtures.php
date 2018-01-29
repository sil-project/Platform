<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
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
use Sil\Component\Stock\Model\Warehouse;

/**
 * Description of WarehouseFixtures.
 *
 * @author glenn
 */
class WarehouseFixtures extends Fixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function load(ObjectManager $manager)
    {
        $wh = Warehouse::createDefault('WH1', 'Entrepôt n°1');

        $manager->persist($wh);
        $manager->flush();

        // other fixtures can get this object using the 'admin-user' name
        $this->addReference('wh-1', $wh);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
