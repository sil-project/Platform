<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Domain\Entity;

use DateTime;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
trait ProgressStateAwareTrait
{
    public function beDraft(): void
    {
        $this->setState($this->getState()->toDraft());
    }

    public function beConfirmed(): void
    {
        $this->setState($this->getState()->toConfirmed());
    }

    public function bePartiallyAvailable(): void
    {
        $this->setState($this->getState()->toPartiallyAvailable());
    }

    public function beAvailable(): void
    {
        $this->setState($this->getState()->toAvailable());
    }

    public function beDone(): void
    {
        $this->setState($this->getState()->toDone());
        $this->setCompletedAt(new DateTime());
    }

    public function beCancel(): void
    {
        $this->setState($this->getState()->toCancel());
    }

    /**
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->getState()->isDraft();
    }

    /**
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->getState()->isConfirmed();
    }

    /**
     * @return bool
     */
    public function isPartiallyAvailable(): bool
    {
        return $this->getState()->isPartiallyAvailable();
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->getState()->isAvailable();
    }

    /**
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->getState()->isDone();
    }

    /**
     * @return bool
     */
    public function isCancel(): bool
    {
        return $this->getState()->isCancel();
    }

    /**
     * @return bool
     */
    public function isToDo(): bool
    {
        return $this->getState()->isToDo();
    }

    public function isInProgress(): bool
    {
        return $this->getState()->isInProgress();
    }
}
