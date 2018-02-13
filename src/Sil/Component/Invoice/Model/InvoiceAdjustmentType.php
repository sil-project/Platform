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
class InvoiceAdjustmentType
{
    const TYPE_PLUS = 2;
    const TYPE_MINUS = 1;
    const TYPE_NEUTRAL = 0;

    /**
     * value.
     *
     * @var int
     */
    protected $value;

    /**
     * Prevent constructor call.
     */
    private function __construct(int $value)
    {
        $this->value = $value;
    }

    public static function plus(): self
    {
        return new static(self::TYPE_PLUS);
    }

    public static function minus(): self
    {
        return new static(self::TYPE_MINUS);
    }

    public static function neutral(): self
    {
        return new static(self::TYPE_NEUTRAL);
    }

    public function isPlus(): bool
    {
        return $this->value === self::TYPE_PLUS;
    }

    public function isMinus(): bool
    {
        return $this->value === self::TYPE_MINUS;
    }

    public function isNeutral(): bool
    {
        return $this->value === self::TYPE_NEUTRAL;
    }
}
