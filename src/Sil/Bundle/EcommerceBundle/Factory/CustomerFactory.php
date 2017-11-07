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

namespace Librinfo\EcommerceBundle\Factory;

use Librinfo\CRMBundle\CodeGenerator\CustomerCodeGenerator;
use Librinfo\CRMBundle\Entity\Organism;
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
     * @param CustomerCodeGenerator $codeGenerator
     */
    public function __construct(FactoryInterface $baseFactory, CustomerCodeGenerator $codeGenerator)
    {
        $this->baseFactory = $baseFactory;
        $this->codeGenerator = $codeGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function createNew()
    {
        $organism = new Organism();
        $organism->setIsCustomer(true);

        $organism->setCustomerCode($this->codeGenerator->generate($organism));

        return $organism;
    }
}
