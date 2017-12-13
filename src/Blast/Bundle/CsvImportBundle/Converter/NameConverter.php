<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\CsvImportBundle\Converter;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Blast\Bundle\CsvImportBundle\Mapping\MappingConfiguration;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class NameConverter implements NameConverterInterface
{
    /**
     * @var array
     */
    private $names;

    /**
     * @var array
     */
    private $mapping;

    public function __construct(MappingConfiguration $mappingConf)
    {
        $this->mapping = $mappingConf->getMapping();

        //$this->configureNames($entityClass);
    }

    /**
     * This is not used.
     */
    public function normalize($propertyName)
    {
        return $propertyName;
    }

    public function denormalize($propertyName)
    {
        if (!isset($this->names)) {
            throw new \Exception(self::class . ' configureName not called ');
        }

        return isset($this->names[$propertyName]) ? $this->names[$propertyName] : $propertyName;
    }

    /**
     * @param string $entityClass entity class FQDN
     */
    public function configureNames($entityClass)
    {
        /* @warning last call set the current Mapping States, @todo find a clean way to do it */
        if (!key_exists($entityClass, $this->mapping)) {
            throw new \Exception(self::class . ' cannot handle this entity class: ' . $entityClass);
        }
        $this->names = $this->mapping[$entityClass]['fields'];
    }
}
