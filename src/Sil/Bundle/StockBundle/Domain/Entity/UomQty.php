<?php

declare(strict_types=1);

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Domain\Entity;

/**
 * Value Object which represent a quantity in a specific Uom.
 *
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class UomQty
{
    /**
     * @var Uom
     */
    protected $uom;

    /**
     * @var float
     */
    protected $value;

    public function __construct(Uom $uom, float $value)
    {
        $this->uom = $uom;
        $this->value = $value;
    }

    public function getUom(): Uom
    {
        return $this->uom;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setUom(Uom $uom): void
    {
        $this->uom = $uom;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @param float $value
     *
     * @return UomQty
     */
    public function copyWithValue($value)
    {
        return new self($this->getUom(), $value);
    }

    /**
     * @param Uom $toUom
     *
     * @return UomQty
     */
    public function convertTo(Uom $toUom)
    {
        if ($this->getUom() == $toUom) {
            return $this;
        }
        $convertedQty = $this->convertValueTo($toUom);

        return new self($toUom, $convertedQty);
    }

    /**
     * @param Uom $toUom
     *
     * @return float
     */
    public function convertValueTo(Uom $toUom)
    {
        return $this->uom->convertValueTo($this->value, $toUom);
    }

    /**
     * @param UomQty $qty
     *
     * @return UomQty
     */
    public function increasedBy(UomQty $qty)
    {
        $convertedValue = $qty->convertValueTo($this->uom);
        $newValue = $this->value + $convertedValue;

        return new self($this->uom, $newValue);
    }

    /**
     * @param UomQty $qty
     *
     * @return UomQty
     */
    public function decreasedBy(UomQty $qty)
    {
        $convertedValue = $qty->convertValueTo($this->uom);

        if ($convertedValue > $this->value) {
            throw new \InvalidArgumentException(
                'A quantity cannot be negative');
        }

        $newValue = $this->value - $convertedValue;

        return new self($this->uom, $newValue);
    }

    /**
     * @param float $multValue
     *
     * @return UomQty
     */
    public function multipliedBy($multValue)
    {
        if ($multValue <= 0) {
            throw new \InvalidArgumentException(
                'A quantity cannot be multiplied by zero or a negative value');
        }
        $newValue = $this->value * $multValue;

        return new self($this->uom, $newValue);
    }

    /**
     * @param float $divValue
     *
     * @return UomQty
     */
    public function dividedBy($divValue)
    {
        if ($divValue <= 0) {
            throw new \InvalidArgumentException(
                'A quantity cannot be divided by zero or a negative value');
        }
        $newValue = $this->value / $divValue;

        return new self($this->uom, $newValue);
    }

    /**
     * @param UomQty $qty
     *
     * @return bool
     */
    public function isEqualTo(UomQty $qty)
    {
        $convertedValue = $qty->convertValueTo($this->uom);

        return $this->value == $convertedValue;
    }

    /**
     * @param UomQty $qty
     *
     * @return bool
     */
    public function isGreaterThan(UomQty $qty)
    {
        $convertedValue = $qty->convertValueTo($this->uom);

        return $this->value > $convertedValue;
    }

    /**
     * @param UomQty $qty
     *
     * @return bool
     */
    public function isGreaterOrEqualTo(UomQty $qty)
    {
        $convertedValue = $qty->convertValueTo($this->uom);

        return $this->value >= $convertedValue;
    }

    /**
     * @param UomQty $qty
     *
     * @return bool
     */
    public function isSmallerThan(UomQty $qty)
    {
        $convertedValue = $qty->convertValueTo($this->uom);

        return $this->value < $convertedValue;
    }

    /**
     * @param UomQty $qty
     *
     * @return bool
     */
    public function isSmallerOrEqualTo(UomQty $qty)
    {
        $convertedValue = $qty->convertValueTo($this->uom);

        return $this->value <= $convertedValue;
    }

    /**
     * @return bool
     */
    public function isZero()
    {
        return $this->value == 0;
    }

    public function __toString()
    {
        return $this->value . ' ' . $this->uom->getName();
    }
}
