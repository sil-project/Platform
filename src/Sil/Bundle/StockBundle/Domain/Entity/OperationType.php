<?php
declare(strict_types=1);

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Sil\Bundle\StockBundle\Domain\Entity;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class OperationType
{

    const INTERNAL_TRANSFER = 'internalTransfer';
    const RECEIPT = 'receipt';
    const SHIPPING = 'shipping';

    /**
     * @var string
     */
    private $value;

    public static function internalTransfer()
    {
        return new self(self::INTERNAL_TRANSFER);
    }

    public static function receipt()
    {
        return new self(self::RECEIPT);
    }

    public static function shipping()
    {
        return new self(self::SHIPPING);
    }

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * 
     * @return bool
     */
    public function isInternalTransfer(): bool
    {
        return $this->value == self::INTERNAL_TRANSFER;
    }

    /**
     * 
     * @return bool
     */
    public function isReceipt(): bool
    {
        return $this->value == self::RECEIPT;
    }

    /**
     * 
     * @return bool
     */
    public function isShipping(): bool
    {
        return $this->value == self::SHIPPING;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public static function getTypes()
    {
        return [
            self::internalTransfer(),
            self::receipt(),
            self::shipping(),
        ];
    }

    public static function getTypeValues()
    {
        return [
            self::INTERNAL_TRANSFER,
            self::RECEIPT,
            self::SHIPPING
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
