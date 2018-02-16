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

interface AdjustmentTypeInterface
{
    /**
     * FixedValue constructor.
     *
     * @return AdjustmentTypeInterface
     */
    public static function fixedValue(): self;

    /**
     * Rate constructor.
     *
     * @return AdjustmentTypeInterface
     */
    public static function rate(): self;

    /**
     * Adjustment is FixedValue.
     *
     * @return bool
     */
    public function isFixedValue(): bool;

    /**
     * Adjustment is Rate.
     *
     * @return bool
     */
    public function isRate(): bool;

    /**
     * Gets adjustment type value.
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * List all available adjustment types.
     *
     * Array format must be as
     * [
     *     'ADJUSTMENT_TYPE' => static::ADJUSTMENT_TYPE,
     *     [...]
     * ].
     *
     * @return array
     */
    public static function getTypes(): array;
}
