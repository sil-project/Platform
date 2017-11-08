<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Loggable\Mapping\Driver;

use Gedmo\Mapping\Driver\Xml as BaseXml;
use Gedmo\Exception\InvalidMappingException;

/**
 * This is an xml mapping driver for Loggable
 * behavioral extension. Used for extraction of extended
 * metadata from xml specifically for Loggable
 * extension.
 *
 * It changes the behaviour of the original Loggable extension:
 * - the prefix used is "blast" instead of "gedmo"
 * - all fields are versioned by default unless they are tagged unversioned
 * - marks fields from traits as versioned
 *
 * @author Boussekeyt Jules <jules.boussekeyt@gmail.com>
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 * @author Libre Informatique [http://www.libre-informatique.fr]
 * @license GNU GPL v3 (http://www.gnu.org/licenses/)
 *
 * @todo adapt this based on Yaml.php
 */
class Xml extends BaseXml
{
    /**
     * {@inheritdoc}
     */
    public function readExtendedMetadata($meta, array &$config)
    {
        /**
         * @var \SimpleXmlElement
         */
        $xml = $this->_getMapping($meta->name);
        $xmlDoctrine = $xml;

        $xml = $xml->children(self::GEDMO_NAMESPACE_URI);

        if ($xmlDoctrine->getName() == 'entity' || $xmlDoctrine->getName() == 'document' || $xmlDoctrine->getName() == 'mapped-superclass') {
            if (isset($xml->loggable)) {
                /**
                 * @var \SimpleXMLElement;
                 */
                $data = $xml->loggable;
                $config['loggable'] = true;
                if ($this->_isAttributeSet($data, 'log-entry-class')) {
                    $class = $this->_getAttribute($data, 'log-entry-class');
                    if (!$cl = $this->getRelatedClassName($meta, $class)) {
                        throw new InvalidMappingException("LogEntry class: {$class} does not exist.");
                    }
                    $config['logEntryClass'] = $cl;
                }
            }
        }

        if (isset($xmlDoctrine->field)) {
            $this->inspectElementForVersioned($xmlDoctrine->field, $config, $meta);
        }
        if (isset($xmlDoctrine->{'many-to-one'})) {
            $this->inspectElementForVersioned($xmlDoctrine->{'many-to-one'}, $config, $meta);
        }
        if (isset($xmlDoctrine->{'one-to-one'})) {
            $this->inspectElementForVersioned($xmlDoctrine->{'one-to-one'}, $config, $meta);
        }
        if (isset($xmlDoctrine->{'reference-one'})) {
            $this->inspectElementForVersioned($xmlDoctrine->{'reference-one'}, $config, $meta);
        }
        if (isset($xmlDoctrine->{'embedded'})) {
            $this->inspectElementForVersioned($xmlDoctrine->{'embedded'}, $config, $meta);
        }

        if (!$meta->isMappedSuperclass && $config) {
            if (is_array($meta->identifier) && count($meta->identifier) > 1) {
                throw new InvalidMappingException("Loggable does not support composite identifiers in class - {$meta->name}");
            }
            if (isset($config['versioned']) && !isset($config['loggable'])) {
                throw new InvalidMappingException("Class must be annotated with Loggable annotation in order to track versioned fields in class - {$meta->name}");
            }
        }
    }

    /**
     * Searches mappings on element for versioned fields.
     *
     * @param \SimpleXMLElement $element
     * @param array             $config
     * @param object            $meta
     */
    private function inspectElementForVersioned(\SimpleXMLElement $element, array &$config, $meta)
    {
        foreach ($element as $mapping) {
            $mappingDoctrine = $mapping;
            /**
             * @var \SimpleXmlElement
             */
            $mapping = $mapping->children(self::GEDMO_NAMESPACE_URI);

            $isAssoc = $this->_isAttributeSet($mappingDoctrine, 'field');
            $field = $this->_getAttribute($mappingDoctrine, $isAssoc ? 'field' : 'name');

            if (isset($mapping->versioned)) {
                if ($isAssoc && !$meta->associationMappings[$field]['isOwningSide']) {
                    throw new InvalidMappingException("Cannot version [{$field}] as it is not the owning side in object - {$meta->name}");
                }
                $config['versioned'][] = $field;
            }
        }
    }
}
