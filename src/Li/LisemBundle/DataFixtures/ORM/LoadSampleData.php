<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sil\Bundle\CRMBundle\Entity\City;
use Sil\Bundle\CRMBundle\Entity\OrganismInterface;
use Sil\Bundle\SeedBatchBundle\Entity\Plot;
use Sil\Bundle\SeedBatchBundle\Entity\SeedBatch;
use Sil\Bundle\SeedBatchBundle\Entity\SeedFarm;
use Sil\Bundle\VarietyBundle\Entity\Variety;
use Nelmio\Alice\Fixtures\Loader;
use Nelmio\Alice\Persister\Doctrine as DoctrinePersister;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Intl\Intl;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class LoadSampleData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var Loader
     */
    private $aliceLoader;

    /**
     * @var DoctrinePersister
     */
    private $alicePersister;

    /**
     * @var int
     */
    private $nbCities;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 20;
    }

    /**
     * load.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $locale = $this->container->getParameter('locale');
        $this->aliceLoader = new Loader($locale);
        $this->alicePersister = new DoctrinePersister($manager);

        $this->nbCities = $this->countCities();

        $objects = $this->loadYml('sample_data.yml');
        $user = $objects['user'];

        // Created by, Updated by
        foreach ($objects as $object) {
            if (method_exists($object, 'setCreatedBy')) {
                $object->setCreatedBy($user);
            }
            if (method_exists($object, 'setUpdatedBy')) {
                $object->setUpdatedBy($user);
            }
        }

        // Zip, city, country (from database, not using Faker data)
        foreach ($objects as $object) {
            if (method_exists($object, 'setCity')) {
                $this->setZipCityCountry($object);
            }
        }

        // Individual organisms have the same address as their unique contact
        foreach ($objects as $name => $object) {
            if (strpos($name, 'pos_ind_') === 0) {
                $contact = $object->getContact();
                $organism = $object->getOrganism();
                $organism->setName($contact->getFirstname() . ' ' . strtoupper($contact->getName()));
                $organism->setAddress($contact->getAddress());
                $organism->setZip($contact->getZip());
                $organism->setCity($contact->getCity());
                $organism->setCountry($contact->getCountry());
            }
        }

        // Persist objects without code generator
        $registry = $this->container->get('blast_core.code_generators');
        $this->alicePersister->persist(array_filter($objects, function ($o) use ($registry) {
            $class = get_class($o);

            return !$registry->hasGeneratorForClass($class) || in_array($class, [SeedFarm::class, Variety::class]);
        }));

        // Codes
        $customerCodeGenerator = $registry->getCodeGenerator($this->container->getParameter('sil_crm.entity.organism.class'), 'customerCode');
        $supplierCodeGenerator = $registry->getCodeGenerator($this->container->getParameter('sil_crm.entity.organism.class'), 'supplierCode');
        $producerCodeGenerator = $registry->getCodeGenerator($this->container->getParameter('sil_crm.entity.organism.class'), 'seedProducerCode');
        $plotCodeGenerator = $registry->getCodeGenerator($this->container->getParameter('sil_seed_batch.entity.plot.class'), 'code');
        $seedBatchCodeGenerator = $registry->getCodeGenerator($this->container->getParameter('sil_seed_batch.entity.seed_batch.class'), 'code');
        foreach ($objects as $object) {
            if ($object instanceof OrganismInterface) {
                if ($object->isCustomer()) {
                    $object->setCustomerCode($customerCodeGenerator::generate($object));
                    $this->alicePersister->persist([$object]);
                }
                if ($object->isSupplier()) {
                    $object->setSupplierCode($supplierCodeGenerator::generate($object));
                    $this->alicePersister->persist([$object]);
                }
                if ($object->isSeedProducer()) {
                    $object->setSeedProducerCode($producerCodeGenerator::generate($object));
                    $this->alicePersister->persist([$object]);
                }
            }
        }
        foreach ($objects as $object) {
            if ($object instanceof Plot) {
                $object->setCode($plotCodeGenerator::generate($object));
                $this->alicePersister->persist([$object]);
            }
        }
        foreach ($objects as $object) {
            if ($object instanceof SeedBatch) {
                $object->setCode($seedBatchCodeGenerator::generate($object));
                $this->alicePersister->persist([$object]);
            }
        }
    }

    protected function loadYml($filename)
    {
        $objects = $this->aliceLoader->load(__DIR__ . '/' . $filename);

        return $objects;
    }

    /**
     * @return bool
     */
    protected function percent($max)
    {
        return rand(1, 100) <= $max;
    }

    /**
     * @return int
     */
    protected function countCities()
    {
        return $this->manager->getRepository(City::class)
            ->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return City
     */
    protected function randomCity()
    {
        return $this->manager->getRepository(City::class)
            ->createQueryBuilder('c')
            ->getQuery()
            ->setMaxResults(1)
            ->setFirstResult(rand(0, $this->nbCities - 1))
            ->getOneOrNullResult();
    }

    /**
     * @param string $name
     * @param mixed  $object
     */
    protected function setZipCityCountry($object)
    {
        $city = $this->randomCity();
        if (method_exists($object, 'setZip')) {
            $object->setZip($city->getZip());
        }
        if (method_exists($object, 'setCity')) {
            $object->setCity($city->getCity());
        }
        if (method_exists($object, 'setCountry')) {
            $object->setCountry(Intl::getRegionBundle()->getCountryName($city->getCountryCode()));
        }
    }
}
