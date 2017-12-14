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
use Blast\Bundle\CsvImportBundle\Mapping\MappingConfiguration;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer as BaseObjectNormalizer;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ObjectNormalizer extends BaseObjectNormalizer
{
 
    /**
     * @var MappingConfiguration
     */
    private $mappingConf;

    /**
     * @var NameConverter
     */
    private $converter;

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em, MappingConfiguration $mappingConf, NameConverter $nameConverter)
    {
        parent::__construct(null, $nameConverter);
        $this->em = $em;
        $this->mappingConf = $mappingConf->getMapping();
        //$this->mapping = $this->getAssociationMapping();
        $this->converter = $nameConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        $this->converter->configureNames($class);
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
        $class = get_class($object);

        if (array_key_exists('associations', $this->mappingConf[$class])) {
            if (array_key_exists($attribute, $this->mappingConf[$class]['associations'])) {
                $associationClass = $this->mappingConf[$class]['associations'][$attribute]['entity'];
                $field = $this->mappingConf[$class]['associations'][$attribute]['field'];
                $value = $this->fetchAssociation($associationClass, $field, $value);
            }
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

 
    protected function cleanUpValue(&$value): void
    {
        $value = trim($value);
    }
}
