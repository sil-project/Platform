<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\CsvImportBundle\Normalizer;

use Blast\Bundle\CsvImportBundle\Converter\NameConverter;
use Doctrine\ORM\EntityManager;
// use Sil\Bundle\VarietyBundle\Entity\Family;
// use Sil\Bundle\VarietyBundle\Entity\Genus;
// use Sil\Bundle\VarietyBundle\Entity\Species;
// use Sil\Bundle\VarietyBundle\Entity\Variety;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer as BaseObjectNormalizer;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ObjectNormalizer extends BaseObjectNormalizer
{
    /**
     * @var EntityManager
     */
    //  private $em;

    /**
     * @var array
     */
    private $mappings;

    /**
     // * @param string        $entityClass   entity class FQDN
     // * @param EntityManager $entityManager
     */
    public function __construct(MappingConfiguration $mappingConf, NameConverter $nameConverter)// $entityClass, EntityManager $entityManager)
    {
        /* @todo: use a service */
        //        $nameConverter = new NameConverter($entityClass);
        parent::__construct(null, $nameConverter);
        // $this->em = $entityManager;
        $this->mappings = $this->getMappings();
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        $object = parent::denormalize($data, $class, $format, $context);

        $rc = new \ReflectionClass($class);
        if (method_exists($this, 'postDenormalize' . $rc->getShortName())) {
            $this->{'postDenormalize' . $rc->getShortName()}($object);
        }

        return $object;
    }

    /**
     * {@inheritdoc}
     */
    protected function setAttributeValue($object, $attribute, $value, $format = null, array $context = array())
    {
        $this->cleanUpValue($value);

        $key = get_class($object) . '.' . $attribute;
        if (isset($this->mappings[$key])) {
            list($associationClass, $field) = $this->mappings[$key];
            $value = $this->fetchAssociation($associationClass, $field, $value);
        }

        if ($value === '') {
            $value = null;
        }

        if ($attribute !== '' && $attribute !== null) {
            parent::setAttributeValue($object, $attribute, $value, $format, $context);
        }
    }

    /**
     * @param string $entityClass FQDN
     * @param string $field
     * @param mixed  $value
     *
     * @return object Associated entity
     */
    protected function fetchAssociation($entityClass, $field, $value)
    {
        return $this->em->getRepository($entityClass)->findOneBy([$field => $value]);
    }

    /**
     * @param Species $species
     *
     * @todo remove this
     */
    protected function postDenormalizeSpecies(Species $species)
    {
        //        if ($species->getLegalGerminationRate() === "")
//            $species->setLegalGerminationRate(null);
//        if ($species->getSeedLifespan() === "")
//            $species->setSeedLifespan(null);
//        if ($species->getRaiseDuration() === "")
//            $species->setRaiseDuration(null);
//        if ($species->getTkw() === "")
//            $species->setTkw(null);
    }

    protected function getMappings()
    {
        /* @todo : use association from config file*/
        return [
            Genus::class . '.family'           => [Family::class, 'name'],
            Species::class . '.genus'          => [Genus::class, 'name'],
            Species::class . '.parent_species' => [Species::class, 'name'],
            Variety::class . '.species'        => [Species::class, 'name'],
        ];
    }

    protected function cleanUpValue(&$value): void
    {
        $value = trim($value);
    }
}
