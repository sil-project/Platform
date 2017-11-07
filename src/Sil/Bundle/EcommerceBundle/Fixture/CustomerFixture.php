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

namespace Librinfo\EcommerceBundle\Fixture;

use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;
use Librinfo\EcommerceBundle\Entity\CustomerGroup;
use Doctrine\ORM\EntityManager;
use Librinfo\CRMBundle\CodeGenerator\CustomerCodeGenerator;
use Librinfo\CRMBundle\Entity\Organism;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class CustomerFixture extends AbstractFixture implements FixtureInterface
{
    /**
     * @var EntityManager
     */
    private $customerManager;

    /**
     * @var CustomerCodeGenerator
     */
    private $codeGenerator;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    public function __construct(EntityManager $customerManager, CustomerCodeGenerator $codeGenerator)
    {
        $this->customerManager = $customerManager;
        $this->codeGenerator = $codeGenerator;
    }

    public function getName(): string
    {
        return 'librinfo_customer_customer';
    }

    public function load(array $options): void
    {
        $faker = \Faker\Factory::create();
        $groups = [];

        for ($g = 0; $g < $options['customer_group_number']; ++$g) {
            $code = sprintf('GRP%s', $faker->randomNumber(3));
            $group = new CustomerGroup();
            $group->setCode($code);
            $group->setName("groupe $code");
            $this->customerManager->persist($group);
            $groups[] = $group;
        }
        $this->customerManager->flush();

        for ($i = 0; $i < $options['customer_number']; ++$i) {
            $group = $groups[rand(0, $options['customer_group_number'] - 1)];

            $customer = new Organism();
            $customer
                ->setIsCustomer(true)
                ->setIsIndividual((bool) rand(0, 1))
                ->setEmail(sprintf('%s@%s', $faker->userName(), $options['email_domain']))
                ->setActive(true)
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setName(sprintf('%s %s', $customer->getFirstname(), $customer->getLastname()))
                ->setGroup($group);

            $this->customerManager->persist($customer);

            $customer->setCustomerCode($this->codeGenerator->generate($customer));

            $this->customerManager->flush();
        }
    }

    /**
     * @param ArrayNodeDefinition $optionsNode
     */
    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
            ->integerNode('customer_number')->defaultValue(4)->end()
            ->integerNode('customer_group_number')->defaultValue(2)->end()
            ->scalarNode('email_domain')->defaultValue('libre-informatique.fr')->end();
    }
}
