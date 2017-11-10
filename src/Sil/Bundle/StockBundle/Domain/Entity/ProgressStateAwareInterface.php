<?php

declare(strict_types=1);

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Domain\Entity;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
interface ProgressStateAwareInterface
{
    /**
     * @return ProgressState
     */
    public function getState(): ProgressState;

    public function beDraft(): void;

    public function beConfirmed(): void;

    public function bePartiallyAvailable(): void;

    public function beAvailable(): void;

    public function beDone(): void;

    public function beCancel(): void;

    /**
     * @return bool
     */
    public function isDraft(): bool;

    /**
     * @return bool
     */
    public function isConfirmed(): bool;

    /**
     * @return bool
     */
    public function isPartiallyAvailable(): bool;

    /**
     * @return bool
     */
    public function isAvailable(): bool;

    /**
     * @return bool
     */
    public function isDone(): bool;

    /**
     * @return bool
     */
    public function isCancel(): bool;

    /**
     * @return bool
     */
    public function isToDo(): bool;

    /**
     * @return bool
     */
    public function isInProgress(): bool;
}
