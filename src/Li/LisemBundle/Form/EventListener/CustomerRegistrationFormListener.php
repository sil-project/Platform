<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\Form\EventListener;

use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Sil\Bundle\CRMBundle\Entity\OrganismInterface;
use Blast\Bundle\CoreBundle\CodeGenerator\CodeGeneratorInterface;

class CustomerRegistrationFormListener
{
    /**
     * @var RepositoryInterface
     */
    private $customerRepository;

    /**
     * @var CodeGeneratorInterface
     */
    private $codeGenerator;

    /**
     * @param RepositoryInterface $customerRepository
     */
    public function __construct(RepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function onPostRegister(GenericEvent $event)
    {
        $organism = $event->getSubject();

        if (!$organism instanceof OrganismInterface) {
            throw new UnexpectedTypeException($organism, OrganismInterface::class);
        }

        if ($this->codeGenerator !== null) {
            $organism->setCustomerCode($this->codeGenerator->generate($organism));
        }

        $organism->setIsIndividual(true);
        $organism->setIsCustomer(true);
    }

    /**
     * setCodeGenerator.
     *
     * @param CodeGeneratorInterface $codeGenerator
     */
    public function setCodeGenerator(CodeGeneratorInterface $codeGenerator)
    {
        $this->codeGenerator = $codeGenerator;
    }
}
