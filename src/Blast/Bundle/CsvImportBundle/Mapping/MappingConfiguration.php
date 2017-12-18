<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\CsvImportBundle\Mapping;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\FileLocator;

class MappingConfiguration
{
    /**
     * Mapping configuration of csv datas.
     *
     * @var array
     */
    private $mapping = null;

    public function setMapping(array $newMapping): self
    {
        $this->mapping = $newMapping;

        return $this;
    }

    /**
     * Loads mapping information from yaml config file.
     */
    public function loadMappingFromFile(string $configFile, string $yamlKey = 'csv_mapping'): self
    {
        /**
         * @todo add this as param in a setter with default value
         */

        // $locator = new FileLocator([$configDirectory]);
        // $configFile = $locator->locate($configFileName, null, true);

        /** @todo: use a real object */
        $rawConfig = Yaml::parse(file_get_contents($configFile));

        if (!array_key_exists('csv_mapping', $rawConfig)) {
            throw new \Exception(sprintf('Invalid csv mapping config file ( %s ), missing root key « csv_mapping »', $configFile));
        }

        $this->mapping = $rawConfig[$yamlKey];

        return $this;
    }

    public function getImportClass(): array
    {
        return array_keys($this->GetMapping());
    }

    public function getMapping(): array
    {
        if ($this->mapping === null) {
            //    throw new \Exception('Invalid Null Mapping Config');
            $this->mapping = [];
        }

        return $this->mapping;
    }
}
