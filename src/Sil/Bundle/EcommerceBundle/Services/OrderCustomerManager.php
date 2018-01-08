<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Services;

use Doctrine\ORM\EntityManager;
use Sil\Bundle\EcommerceBundle\Entity\OrderInterface;
use Blast\Bundle\CoreBundle\CodeGenerator\CodeGeneratorInterface;

class OrderCustomerManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var CodeGeneratorInterface
     */
    private $codeGenerator;

    /**
     * @var string
     */
    private $customerClass;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, string $customerClass)
    {
        $this->em = $em;
        $this->customerClass = $customerClass;
    }

    public function associateUserAndAddress(OrderInterface $object)
    {
        $shippingAddress = $object->getShippingAddress();
        $billingAddress = $object->getBillingAddress();
        $givenCustomer = $object->getCustomer();

        $foundCustomer = $this->em->getRepository($this->customerClass)
                       ->findOneBy(array('email' => $givenCustomer->getEmail())); /* As email must be unique */

        $customer = null; /* We love null :) */

        if ($foundCustomer !== null) {
            /* @todo: should not ask user to set a firstname and lastname if the email already exist */
            $customer = $foundCustomer;
        } else {
            $customer = $givenCustomer;
            $customer->setIsIndividual(true);
            $customer->setIsCustomer(true);

            $customer->setFirstname($billingAddress->getFirstName());
            $customer->setLastname($billingAddress->getLastName());
        }

        if ($this->codeGenerator !== null && $customer->getCustomerCode() === null) {
            $customer->setCustomerCode($this->codeGenerator->generate($customer));
        }

        /* @todo: check if addAddress check if adress already exist */
        $customer->addAddress($shippingAddress);
        $customer->addAddress($billingAddress);

        $object->setCustomer($customer);
        $customer->addOrder($object);

        // $this->em->flush($customer);
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
