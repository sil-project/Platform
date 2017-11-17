<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\DataFixtures\Sylius\Factory;

use Doctrine\ORM\EntityManager;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
final class FamilyExampleFactory extends ExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @param Repository $userRepository
     */
    protected $entityManager;

    /**
     * @var string
     */
    protected $entityClass;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->optionsResolver =
            (new OptionsResolver())
                ->setDefault('name', '')
                ->setDefault('latin_name', '')
                ->setDefault('alias', '')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);
        $family = (new \ReflectionClass($this->entityClass))->newInstance();
        $family->setName($options['name']);
        $family->setLatinName($options['latin_name']);
        $family->setAlias($options['alias']);
        $this->setCreator($family);

        return $family;
    }

    /**
     * @param string $entityClass
     */
    public function setEntityClass(string $entityClass): void
    {
        $this->entityClass = $entityClass;
    }
}
