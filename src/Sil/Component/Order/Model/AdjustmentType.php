<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Order\Model;

class AdjustmentType implements AdjustmentTypeInterface
{
    const TYPE_FIXED_VALUE = 'TYPE_FIXED_VALUE';
    const TYPE_RATE = 'TYPE_RATE';

    /**
     * @var string
     */
    protected $value;

    /**
     * {@inheritdoc}
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public static function fixedValue(): AdjustmentTypeInterface
    {
        return new self(static::TYPE_FIXED_VALUE);
    }

    /**
     * {@inheritdoc}
     */
    public static function rate(): AdjustmentTypeInterface
    {
        return new self(static::TYPE_RATE);
    }

    /**
     * {@inheritdoc}
     */
    public function isFixedValue(): bool
    {
        return $this->value === static::TYPE_FIXED_VALUE;
    }

    /**
     * {@inheritdoc}
     */
    public function isRate(): bool
    {
        return $this->value === static::TYPE_RATE;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public static function getTypes(): array
    {
        return [
            'TYPE_FIXED_VALUE'     => static::TYPE_FIXED_VALUE,
            'TYPE_RATE'            => static::TYPE_RATE,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
