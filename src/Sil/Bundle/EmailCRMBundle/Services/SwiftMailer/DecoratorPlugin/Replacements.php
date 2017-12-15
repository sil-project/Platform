<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EmailCRMBundle\Services\SwiftMailer\DecoratorPlugin;

use Doctrine\ORM\EntityManager;
use Sil\Bundle\CRMBundle\Entity\OrganismInterface;

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
        $organism = $this->manager->getRepository(OrganismInterface::class)->findOneBy(array('email' => $address));

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
