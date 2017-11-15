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
use Sil\Bundle\CRMBundle\Entity\Organism;
use Sil\Bundle\SeedBatchBundle\CodeGenerator\PlotCodeGenerator;
use Sil\Bundle\SeedBatchBundle\CodeGenerator\SeedBatchCodeGenerator;
use Sil\Bundle\SeedBatchBundle\CodeGenerator\SeedProducerCodeGenerator;
use Sil\Bundle\SeedBatchBundle\Entity\Plot;
use Sil\Bundle\SeedBatchBundle\Entity\SeedBatch;
use Sil\Bundle\SeedBatchBundle\Entity\SeedFarm;
use Sil\Bundle\VarietyBundle\Entity\Variety;
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
        $varietyRepository = $entityManager->getRepository('SilVarietyBundle:Variety');
        $seedFarmRepository = $entityManager->getRepository('SilSeedBatchBundle:SeedFarm');
        $plotRepository = $entityManager->getRepository('SilSeedBatchBundle:Plot');
        $organismRepository = $entityManager->getRepository('SilCRMBundle:Organism');

        $this->optionsResolver =
                               (new OptionsResolver())
                               ->setDefault('seed_farm', 'LiSem AS')
                               ->setAllowedTypes('seed_farm', ['null', 'string', SeedFarm::class])
                               ->setNormalizer('seed_farm', $this->findOneBy($seedFarmRepository, 'name'))

                               ->setDefault('variety', $this->randomOne($varietyRepository))
                               ->setAllowedTypes('variety', ['null', 'string', Variety::class])
                               ->setNormalizer('variety', $this->findOneBy($varietyRepository, 'name'))

                               ->setDefault('producer', null)
                               ->setAllowedTypes('producer', ['null', 'string', Organism::class])
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

        $seedBatch = new SeedBatch();
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
        $seedFarm = new SeedFarm();
        $seedFarm->setName('LiSem AS')
            ->setCode('LIS');
        $this->entityManager->persist($seedFarm);

        return $seedFarm;
    }

    /**
     * @todo   This should go in a specific fixture
     *
     * @return Organism
     */
    protected function createProducer()
    {
        $producer = new Organism();
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
     * @param Organism $producer
     *
     * @todo   This should go in a specific fixture
     *
     * @return Plot
     */
    protected function createPlot(Organism $producer)
    {
        $plot = new Plot();
        $plot->setName('Parcelle ' . $this->faker->city)
                                   ->setProducer($producer);
        $this->entityManager->persist($plot);

        return $plot;
    }
}
