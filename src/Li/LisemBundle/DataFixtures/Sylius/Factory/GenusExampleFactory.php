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
use Sil\Bundle\VarietyBundle\Entity\FamilyInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
final class GenusExampleFactory extends ExampleFactory implements ExampleFactoryInterface
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
        $familyRepository = $entityManager->getRepository(FamilyInterface::class);

        $this->optionsResolver =
            (new OptionsResolver())
                ->setDefault('name', '')

                ->setDefined('family')
                ->setAllowedTypes('family', ['string', FamilyInterface::class])
                ->setNormalizer('family', $this->findOneBy($familyRepository, 'name'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);
        $genus = (new \ReflectionClass($this->entityClass))->newInstance();
        $genus->setName($options['name']);
        $genus->setFamily($options['family']);
        $this->setCreator($genus);

        return $genus;
    }

    /**
     * @param string $entityClass
     */
    public function setEntityClass(string $entityClass): void
    {
        $this->entityClass = $entityClass;
    }
}
