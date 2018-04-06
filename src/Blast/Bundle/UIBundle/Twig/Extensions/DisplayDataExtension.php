<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UIBundle\Twig\Extensions;

use Twig_Environment;
use Twig_Extension;

class DisplayDataExtension extends Twig_Extension
{
    const DATA_TYPE_BOOLEAN = 'boolean';
    const DATA_TYPE_INTEGER = 'integer';
    const DATA_TYPE_DOUBLE = 'double';
    const DATA_TYPE_STRING = 'string';
    const DATA_TYPE_ARRAY = 'array';
    const DATA_TYPE_OBJECT = 'object';
    const DATA_TYPE_RESOURCE = 'resource';
    const DATA_TYPE_NULL = 'NULL';
    const DATA_TYPE_UNKNOWN = 'unknown type';

    use BlastUIExtensionTrait;

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            $this->registerFunction('display_data', 'displayData', true),
            $this->registerFunction('guess_data_type', 'guessDataType'),
        ];
    }

    public function displayData(Twig_Environment $env, $data, $dataType = null)
    {
        return $env->render('@BlastUI/DataType/' . ($dataType !== null ? $dataType : $this->guessDataType($data)) . '.html.twig', [
            'data' => $data,
        ]);
    }

    public function guessDataType($data): string
    {
        $type = str_replace(' type', '', self::DATA_TYPE_UNKNOWN);

        switch (gettype($data)) {
            case self::DATA_TYPE_BOOLEAN:
                $type = self::DATA_TYPE_BOOLEAN;
                break;
            case self::DATA_TYPE_INTEGER:
                $type = self::DATA_TYPE_INTEGER;
                break;
            case self::DATA_TYPE_DOUBLE:
                $type = self::DATA_TYPE_DOUBLE;
                break;
            case self::DATA_TYPE_STRING:
                $type = self::DATA_TYPE_STRING;
                break;
            case self::DATA_TYPE_ARRAY:
                $type = self::DATA_TYPE_ARRAY;
                break;
            case self::DATA_TYPE_OBJECT:
                $type = self::DATA_TYPE_OBJECT;
                break;
            case self::DATA_TYPE_NULL:
                $type = strtolower(self::DATA_TYPE_NULL);
                break;
            case self::DATA_TYPE_RESOURCE:
            case self::DATA_TYPE_UNKNOWN:
            default:
                break;
        }

        return $type;
    }
}
