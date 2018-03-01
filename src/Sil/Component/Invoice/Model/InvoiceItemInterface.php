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
interface InvoiceItemInterface
{
    /**
     * Get the value of label.
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Get the value of unit price.
     *
     * @return float
     */
    public function getUnitPrice(): float;

    /**
     * Get the value of quantity.
     *
     * @return float
     */
    public function getQuantity(): float;

    /**
     * Get the value of tax rate.
     *
     * @return float
     */
    public function getTaxRate(): float;
}
