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
class InvoiceAdjustment implements ResourceInterface, InvoiceAdjustmentInterface
{
    /**
     * label.
     *
     * @var string
     */
    protected $label;

    /**
     * type.
     *
     * @var InvoiceAdjustmentType
     */
    protected $type;

    /**
     * value.
     *
     * @var float
     */
    protected $value;

    /**
     * @param string $label
     * @param string $type
     * @param float  $value
     */
    public function __construct(string $label, InvoiceAdjustmentType $type, float $value)
    {
        $this->label = $label;
        $this->type = $type;
        $this->value = $value;
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
     * Set the value of label.
     *
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * Get the value of type.
     *
     * @return int
     */
    public function getType(): InvoiceAdjustmentType
    {
        return $this->type;
    }

    /**
     * Set the value of type.
     *
     * @param int $type
     */
    public function setType(InvoiceAdjustmentType $type): void
    {
        $this->type = $type;
    }

    /**
     * Get the value of value.
     *
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * Set the value of value.
     *
     * @param float $value
     */
    public function setValue(float $value): void
    {
        $this->value = $value;
    }
}
