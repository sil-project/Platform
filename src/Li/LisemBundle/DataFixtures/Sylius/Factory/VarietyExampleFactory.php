<?php

/*
 * This file is part of the Lisem Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\DataFixtures\Sylius\Factory;

use Doctrine\ORM\EntityManager;
use Sil\Bundle\VarietyBundle\Entity\Species;
use Sil\Bundle\VarietyBundle\Entity\Variety;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
final class VarietyExampleFactory extends ExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @param Repository $userRepository
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $speciesRepository = $entityManager->getRepository('SilVarietyBundle:Species');

        $this->optionsResolver =
            (new OptionsResolver())
                ->setDefined('name')

                ->setDefault('latin_name', '')

                ->setDefault('alias', '')

                ->setDefault('description', '')

                ->setDefined('code')

                ->setDefined('species')
                ->setAllowedTypes('species', ['string', Species::class])
                ->setNormalizer('species', $this->findOneBy($speciesRepository, 'name'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);
        $variety = new Variety();
        $variety->setName($options['name']);
        $variety->setLatinName($options['latin_name']);
        $variety->setAlias($options['alias']);
        $variety->setDescription($options['description']);
        $variety->setCode($options['code']);
        $variety->setSpecies($options['species']);
        $this->setCreator($variety);

        return $variety;
    }
}
