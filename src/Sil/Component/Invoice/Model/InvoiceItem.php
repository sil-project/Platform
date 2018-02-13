<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Invoice\Model;

use Blast\Component\Resource\Model\ResourceInterface;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class InvoiceItem implements ResourceInterface, InvoiceItemInterface
{
    /**
     * label.
     *
     * @var string
     */
    protected $label;

    /**
     * description.
     *
     * @var string
     */
    protected $description;

    /**
     * unit price.
     *
     * @var float
     */
    protected $unitPrice;

    /**
     * quantity.
     *
     * @var float
     */
    protected $quantity;

    /**
     * tax rate.
     *
     * @var float
     */
    protected $taxRate;

    /**
     * @param string $label
     * @param float  $unitPrice
     * @param float  $quantity
     * @param float  $taxRate
     */
    public function __construct(string $label, float $unitPrice, float $quantity, float $taxRate)
    {
        $this->label = $label;
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
        $this->taxRate = $taxRate;
    }

    /**
     * Get the value of label.
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Get the value of description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the value of description.
     *
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Get the value of unit price.
     *
     * @return float
     */
    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    /**
     * Get the value of quantity.
     *
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * Get the value of tax rate.
     *
     * @return float
     */
    public function getTaxRate(): float
    {
        return $this->taxRate;
    }
}
