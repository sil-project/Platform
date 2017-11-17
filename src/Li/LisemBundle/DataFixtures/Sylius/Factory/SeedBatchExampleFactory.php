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
use Sil\Bundle\CRMBundle\Entity\OrganismInterface;
use Sil\Bundle\SeedBatchBundle\CodeGenerator\PlotCodeGenerator;
use Sil\Bundle\SeedBatchBundle\CodeGenerator\SeedBatchCodeGenerator;
use Sil\Bundle\SeedBatchBundle\CodeGenerator\SeedProducerCodeGenerator;
use Sil\Bundle\SeedBatchBundle\Entity\Plot;
use Sil\Bundle\SeedBatchBundle\Entity\SeedFarm;
use Sil\Bundle\VarietyBundle\Entity\VarietyInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 *
 * @todo We should create fixtures for seed producers, plots and seed farms
 */
final class SeedBatchExampleFactory extends ExampleFactory implements ExampleFactoryInterface
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
     * @var SeedBatchCodeGenerator
     */
    protected $seedBatchCodeGenerator;

    /**
     * @var SeedProducerCodeGenerator
     */
    protected $seedProducerCodeGenerator;

    /**
     * @var PlotCodeGenerator
     */
    protected $plotCodeGenerator;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var string
     */
    protected $seedBatchClass;

    /**
     * @var string
     */
    protected $seedFarmClass;

    /**
     * @var string
     */
    protected $organismClass;

    /**
     * @var string
     */
    protected $plotClass;

    public function __construct(
        EntityManager $entityManager,
        SeedBatchCodeGenerator $seedBatchCodeGenerator,
        SeedProducerCodeGenerator $seedProducerCodeGenerator,
        PlotCodeGenerator $plotCodeGenerator
    ) {
        $this->entityManager = $entityManager;
        $this->seedBatchCodeGenerator = $seedBatchCodeGenerator;
        $this->seedProducerCodeGenerator = $seedProducerCodeGenerator;
        $this->plotCodeGenerator = $plotCodeGenerator;
        $this->faker = \Faker\Factory::create();
        $varietyRepository = $entityManager->getRepository(VarietyInterface::class);
        $seedFarmRepository = $entityManager->getRepository(SeedFarm::class);
        $plotRepository = $entityManager->getRepository(Plot::class);
        $organismRepository = $entityManager->getRepository(OrganismInterface::class);

        $this->optionsResolver =
                               (new OptionsResolver())
                               ->setDefault('seed_farm', 'LiSem AS')
                               ->setAllowedTypes('seed_farm', ['null', 'string', SeedFarm::class])
                               ->setNormalizer('seed_farm', $this->findOneBy($seedFarmRepository, 'name'))

                               ->setDefault('variety', $this->randomOne($varietyRepository))
                               ->setAllowedTypes('variety', ['null', 'string', VarietyInterface::class])
                               ->setNormalizer('variety', $this->findOneBy($varietyRepository, 'name'))

                               ->setDefault('producer', null)
                               ->setAllowedTypes('producer', ['null', 'string', OrganismInterface::class])
                               // TODO: Wrong query. Should be: "find seed producer with name..."
                               ->setNormalizer('producer', $this->findOneBy($organismRepository, 'name'))

                               ->setDefault('plot', null)
                               ->setAllowedTypes('plot', ['null', 'string', Plot::class])
                               ->setNormalizer('plot', $this->findOneBy($plotRepository, 'name'))

                               ->setDefault('production_year', $this->faker->dateTimeBetween('-5 years', 'now')->format('Y'))
                               ->setAllowedTypes('production_year', ['string'])

                               ->setDefault('batch_number', '1')
                               ->setAllowedTypes('batch_number', ['string'])

                               ->setDefault('description', '')
                               ;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        $producer = $options['producer'] ?: $this->createProducer();
        $plot = $options['plot'] ?: $this->createPlot($producer);

        $seedBatch = (new \ReflectionClass($this->seedBatchClass))->newInstance();
        $seedBatch->setVariety($options['variety'])
            ->setSeedFarm($options['seed_farm'] ?: $this->createSeedFarm())
            ->setProducer($producer)
            ->setPlot($plot)
            ->setProductionYear($options['production_year'])
            ->setBatchNumber($options['batch_number'])
            ->setDescription($options['description']);
        $seedBatch->setCode($this->seedBatchCodeGenerator->generate($seedBatch));

        return $seedBatch;
    }

    /**
     * @todo   This should go in a specific fixture
     *
     * @return SeedFarm
     */
    protected function createSeedFarm()
    {
        $seedFarm = (new \ReflectionClass($this->seedFarmClass))->newInstance();
        $seedFarm->setName('LiSem AS')
            ->setCode('LIS');
        $this->entityManager->persist($seedFarm);

        return $seedFarm;
    }

    /**
     * @todo   This should go in a specific fixture
     *
     * @return OrganismInterface
     */
    protected function createProducer()
    {
        $producer = (new \ReflectionClass($this->organismClass))->newInstance();
        $producer->setName($this->faker->company)
            ->setSeedProducer(true)
            ->setEmail($email = $this->faker->email);
        // No more fluent
        $producer->setEmailCanonical($email);
        $producer->setSeedProducerCode($this->seedProducerCodeGenerator->generate($producer));
        $this->entityManager->persist($producer);

        return $producer;
    }

    /**
     * @param OrganismInterface $producer
     *
     * @todo   This should go in a specific fixture
     *
     * @return Plot
     */
    protected function createPlot(OrganismInterface $producer)
    {
        $plot = (new \ReflectionClass($this->plotClass))->newInstance();
        $plot
            ->setName('Parcelle ' . $this->faker->city)
            ->setProducer($producer);
        $this->entityManager->persist($plot);

        return $plot;
    }

    /**
     * @param string $seedBatchClass
     */
    public function setSeedBatchClass(string $seedBatchClass): void
    {
        $this->seedBatchClass = $seedBatchClass;
    }

    /**
     * @param string $seedFarmClass
     */
    public function setSeedFarmClass(string $seedFarmClass): void
    {
        $this->seedFarmClass = $seedFarmClass;
    }

    /**
     * @param string $organismClass
     */
    public function setOrganismClass(string $organismClass): void
    {
        $this->organismClass = $organismClass;
    }

    /**
     * @param string $plotClass
     */
    public function setPlotClass(string $plotClass): void
    {
        $this->plotClass = $plotClass;
    }
}
