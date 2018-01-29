<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Stock\Model;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class LocationType
{
    const INTERNAL = 'internal';
    const SUPPLIER = 'supplier';
    const CUSTOMER = 'customer';
    const VIRTUAL = 'virtual';
    const SCRAP = 'scrap';

    /**
     * @var string
     */
    protected $value;

    public static function internal()
    {
        return new self(self::INTERNAL);
    }

    public static function supplier()
    {
        return new self(self::SUPPLIER);
    }

    public static function customer()
    {
        return new self(self::CUSTOMER);
    }

    public static function virtual()
    {
        return new self(self::VIRTUAL);
    }

    public static function scrap()
    {
        return new self(self::SCRAP);
    }

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function isInternal(): bool
    {
        return $this->value == self::INTERNAL;
    }

    public function isSupplier(): bool
    {
        return $this->value == self::SUPPLIER;
    }

    public function isCustomer(): bool
    {
        return $this->value == self::CUSTOMER;
    }

    public function isVirtual(): bool
    {
        return $this->value == self::VIRTUAL;
    }

    public function isScrap(): bool
    {
        return $this->value == self::SCRAP;
    }

    public function getTypes()
    {
        return [
            self::customer(),
            self::internal(),
            self::supplier(),
            self::scrap(),
            self::virtual(),
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }
}
