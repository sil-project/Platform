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

use DomainException;

interface OrderStateInterface
{
    /**
     * List all available states.
     *
     * Array format must be as
     * [
     *     'STATE' => static::STATE,
     *     [...]
     * ].
     *
     * @return array
     */
    public static function getStates(): array;

    /**
     * Gets current state value.
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * Method used to check if Order is editable before changing its data.
     *
     * @throws DomainException
     */
    public function allowDataChanges(): void;

    /**
     * Draft state constructor.
     *
     * @return OrderStateInterface
     */
    public static function draft(): self;

    /**
     * Validated state constructor.
     *
     * @return OrderStateInterface
     */
    public static function validated(): self;

    /**
     * Cancelled state constructor.
     *
     * @return OrderStateInterface
     */
    public static function cancelled(): self;

    /**
     * Fulfilled state constructor.
     *
     * @return OrderStateInterface
     */
    public static function fulfilled(): self;

    /**
     * Deleted state constructor.
     *
     * @return OrderStateInterface
     */
    public static function deleted(): self;

    /**
     * Current state is Draft.
     *
     * @return bool
     */
    public function isDraft(): bool;

    /**
     * Current state is Validated.
     *
     * @return bool
     */
    public function isValidated(): bool;

    /**
     * Current state is Cancelled.
     *
     * @return bool
     */
    public function isCancelled(): bool;

    /**
     * Current state is Fulfilled.
     *
     * @return bool
     */
    public function isFulfilled(): bool;

    /**
     * Current state is Deleted.
     *
     * @return bool
     */
    public function isDeleted(): bool;

    /**
     * Apply transition delete.
     */
    public function toDelete(): self;

    /**
     * Apply transition validate.
     */
    public function toValidate(): self;

    /**
     * Apply transition fulfill.
     */
    public function toFulfill(): self;

    /**
     * Apply transition cancel.
     */
    public function toCancel(): self;
}
