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

trait MessageStateAwareTrait
{
    /**
     * Gets current state.
     *
     * @return MessageStateInterface
     */
    public function getState(): MessageStateInterface
    {
        return $this->state;
    }

    /**
     * @internal
     *
     * @param MessageStateInterface $state
     */
    public function setState(MessageStateInterface $state): void
    {
        $this->state = $state;
    }

    /**
     * Sets current state to validated.
     */
    public function beValidated(): void
    {
        $this->getState()->toValidate();
    }

    /**
     * Sets current state to sent.
     */
    public function beSent(): void
    {
        $this->getState()->toSent();
    }

    /**
     * Sets current state to deleted.
     */
    public function beDeleted(): void
    {
        $this->getState()->toDelete();
    }

    /**
     * Checks if current state is draft.
     *
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->getState()->isDraft();
    }

    /**
     * Checks if current state is validated.
     *
     * @return bool
     */
    public function isValidated(): bool
    {
        return $this->getState()->isValidated();
    }

    /**
     * Checks if current state is sent.
     *
     * @return bool
     */
    public function isSent(): bool
    {
        return $this->getState()->isSent();
    }

    /**
     * Checks if current state is deleted.
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->getState()->isDeleted();
    }
}
