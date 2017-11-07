<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EmailCRMBundle\Services\SwiftMailer\DecoratorPlugin;

use Doctrine\ORM\EntityManager;

class Replacements implements \Swift_Plugins_Decorator_Replacements
{
    /**
     * @var EntityManager
     * */
    private $manager;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Returns Contact info if SilCRMBundle is installed.
     *
     * @param type $address
     *
     * @return type
     */
    public function getReplacementsFor($address)
    {
        $organism = $this->manager->getRepository('SilCRMBundle:Organism')->findOneBy(array('email' => $address));

        if ($organism) {
            if ($organism->isIndividual()) {
                return array(
                    '{prenom}' => $organism->getFirstName(),
                    '{nom}'    => $organism->getLastName(),
                    '{titre}'  => $organism->getTitle(),
                );
            } else {
                return array(
                    '{nom}' => $organism->getName(),
                );
            }
        }
    }
}
