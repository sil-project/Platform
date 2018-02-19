<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Emailing\Model;

interface MessageStateInterface
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
     * Draft state constructor.
     *
     * @return MessageStateInterface
     */
    public static function draft(): self;

    /**
     * Validated state constructor.
     *
     * @return MessageStateInterface
     */
    public static function validated(): self;

    /**
     * Sent state constructor.
     *
     * @return MessageStateInterface
     */
    public static function sent(): self;

    /**
     * Deleted state constructor.
     *
     * @return MessageStateInterface
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
     * Current state is Sent.
     *
     * @return bool
     */
    public function isSent(): bool;

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
     * Apply transition cancel.
     */
    public function toSent(): self;
}
