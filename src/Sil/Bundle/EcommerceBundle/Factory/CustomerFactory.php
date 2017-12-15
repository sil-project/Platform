<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Factory;

use Sil\Bundle\CRMBundle\CodeGenerator\CustomerCodeGenerator;
use Sylius\Component\Resource\Factory\FactoryInterface;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class CustomerFactory implements FactoryInterface
{
    /**
     * @var CustomerCodeGenerator
     */
    private $codeGenerator;

    /**
     * The decorated factory service.
     *
     * @var FactoryInterface
     */
    private $baseFactory;

    /**
     * @var string
     */
    private $customerClass;

    /**
     * @param CustomerCodeGenerator $codeGenerator
     */
    public function __construct(string $customerClass, FactoryInterface $baseFactory, CustomerCodeGenerator $codeGenerator)
    {
        $this->customerClass = $customerClass;
        $this->baseFactory = $baseFactory;
        $this->codeGenerator = $codeGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function createNew()
    {
        $organism = new $this->customerClass();
        $organism->setIsCustomer(true);
        $organism->setCustomerCode($this->codeGenerator->generate($organism));

        return $organism;
    }
}
