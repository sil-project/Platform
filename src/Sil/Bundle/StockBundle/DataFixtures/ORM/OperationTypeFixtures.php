<?php

/*
 *
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
use Sil\Bundle\StockBundle\Domain\Entity\OperationType;

/**
 * @author glenn
 */
class OperationTypeFixtures extends Fixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function load(ObjectManager $manager)
    {
        $oc1 = OperationType::createDefault('REC', 'Réception');
        $oc2 = OperationType::createDefault('EXP', 'Expédition');
        $oc3 = OperationType::createDefault('INT', 'Transfert interne');
        $oc4 = OperationType::createDefault('INV', 'Inventaire');
        $manager->persist($oc1);
        $manager->persist($oc2);
        $manager->persist($oc3);
        $manager->persist($oc4);
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
