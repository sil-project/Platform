<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\CsvImportBundle\Services;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\FileLocator;

class CsvMappingConfiguration
{
    /**
     * Mapping configuration of csv datas.
     *
     * @var array
     */
    private $mapping = null;

    /**
     * @var CsvMappingConfiguration
     * @todo remove this
     */
    private static $instance = null;

    /**
     * @var string
     */
    private $configDirectory;

    /**
     * @var string
     */
    private $configFileName;
    
    
    
    /**
     * Loads Csv mapping information from yaml config file.
     */
    private function loadCsvMapping(): void
    {
        /** @todo add this as param in a setter with default value */
        $this->configDirectory = 'src/Li/LisemBundle/Resources/config';
        $this->configFileName = 'csv_import.yml';
        
        $locator = new FileLocator([$this->configDirectory]);
        $configFile = $locator->locate($this->configFileName, null, true);

        /** @todo: use a real object */
        $rawConfig = Yaml::parse(file_get_contents($configFile));

        if (!array_key_exists('csv_mapping', $rawConfig)) {
            throw new \Exception(sprintf('Invalid csv mapping config file ( %s ), missing root key « csv_mapping »', $configFile));
        }

        $this->mapping = $rawConfig['csv_mapping'];
    }

    /**
     * @return array
     */
    public function getMapping(): array
    {
        if ($this->mapping === null) {
            $this->loadCsvMapping();
        }

        return $this->mapping;
    }

   
    /**
     * @return CsvMappingConfiguration
     * @todo remove this
     */
    public static function getInstance(): CsvMappingConfiguration
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
