<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Invoice\Model;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
interface InvoiceAdjustmentInterface
{
    /**
     * Get the value of label.
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Get the value of type.
     *
     * @return int
     */
    public function getType(): InvoiceAdjustmentType;

    /**
     * Get the value of value.
     *
     * @return float
     */
    public function getValue(): float;
}
