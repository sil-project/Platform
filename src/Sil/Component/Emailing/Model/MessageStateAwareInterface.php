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

interface MessageStateAwareInterface
{
    /**
     * Gets current message state.
     *
     * @return MessageStateInterface
     */
    public function getState(): MessageStateInterface;

    /**
     * Sets current state to validated.
     */
    public function beValidated(): void;

    /**
     * Sets current state to sent.
     */
    public function beSent(): void;

    /**
     * Sets current state to deleted.
     */
    public function beDeleted(): void;

    /**
     * Checks if current state is draft.
     *
     * @return bool
     */
    public function isDraft(): bool;

    /**
     * Checks if current state is validated.
     *
     * @return bool
     */
    public function isValidated(): bool;

    /**
     * Checks if current state is sent.
     *
     * @return bool
     */
    public function isSent(): bool;

    /**
     * Checks if current state is deleted.
     *
     * @return bool
     */
    public function isDeleted(): bool;
}
