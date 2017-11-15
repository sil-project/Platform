<?php

declare(strict_types=1);

/*
 * This file is part of the Lisem Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\Twig;

class UnitConverter extends \Twig_Extension
{
    /**
     * @TODO: Merge with stock bundle unit manager
     *
     * @var [type]
     */
    private $managedUnitTypes = [
        'g' => [
            'mg' => 1 / 1000,
            'g'  => 1,
            'Kg' => 1000,
        ],
        'm2' => [
            'm2' => 1,
            'ha' => 10000,
        ],
    ];

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('convertValueWithUnit', [$this, 'convertValueWithUnit']),
        ];
    }

    /**
     * @param float  $value
     * @param string $unitType
     * @param string $unitBase
     *
     * @return string
     */
    public function convertValueWithUnit($value, $unitType, $unitBase = null)
    {
        if (!array_key_exists($unitType, $this->managedUnitTypes)) {
            throw new \Exception(
                sprintf(
                    'The unit type « %s » is not managed, available unit types are : %s',
                    $unitType,
                    implode(', ', array_keys($this->managedUnitTypes))
                )
            );
        }

        if (!array_key_exists($unitBase, $this->managedUnitTypes[$unitType])) {
            throw new \Exception(
                sprintf(
                    'The unit « %s » is not managed by unit type « %s », available units are : %s',
                    $unitBase,
                    $unitType,
                    implode(', ', array_keys($this->managedUnitTypes[$unitType]))
                )
            );
        }

        $unitBase ?: $unitType;

        $convertedValue = $value * $this->managedUnitTypes[$unitType][$unitBase];
        $symbol = $unitType;

        foreach (array_reverse($this->managedUnitTypes[$unitType]) as $unitSymbol => $unitRatio) {
            if ($convertedValue / $unitRatio >= 1) {
                $symbol = $unitSymbol;
                $convertedValue = $convertedValue / $unitRatio;
                break;
            }
        }

        return $convertedValue . ' ' . $symbol;
    }
}
