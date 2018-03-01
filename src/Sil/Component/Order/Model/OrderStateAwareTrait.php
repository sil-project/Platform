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

trait OrderStateAwareTrait
{
    /**
     * Gets current state.
     *
     * @return OrderStateInterface
     */
    public function getState(): OrderStateInterface
    {
        return $this->state;
    }

    /**
     * @internal
     *
     * @param OrderStateInterface $state
     */
    public function setState(OrderStateInterface $state): void
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
     * Sets current state to cancelled.
     */
    public function beCancelled(): void
    {
        $this->getState()->toCancel();
    }

    /**
     * Sets current state to fulfilled.
     */
    public function beFulfilled(): void
    {
        $this->getState()->toFulfill();
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
     * Checks if current state is cancelled.
     *
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->getState()->isCancelled();
    }

    /**
     * Checks if current state is fulfilled.
     *
     * @return bool
     */
    public function isFulfilled(): bool
    {
        return $this->getState()->isFulfilled();
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
