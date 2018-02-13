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

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class BusinessCustomerInfo extends CustomerInfo
{
    /**
     * VAT number.
     *
     * @var string
     */
    protected $vatNumber;

    /**
     * @param string $name
     * @param string $vatNumber
     */
    public function __construct(string $name, string $vatNumber)
    {
        $this->name = $name;
        $this->vatNumber = $vatNumber;
    }

    /**
     * Get the value of VAT number.
     *
     * @return string
     */
    public function getVatNumber(): string
    {
        return $this->vatNumber;
    }

    /**
     * Set the value of VAT number.
     *
     * @param string $vatNumber
     */
    public function setVatNumber(string $vatNumber): void
    {
        $this->vatNumber = $vatNumber;
    }
}
