<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Stock\Model;

use DomainException;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class ProgressState
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @var ProgressStateMachine
     */
    protected $stateMachine;

    /**
     * @return ProgressState
     */
    public static function draft(): self
    {
        return new self(ProgressStateMachine::DRAFT);
    }

    /**
     * @return ProgressState
     */
    public static function confirmed(): self
    {
        return new self(ProgressStateMachine::CONFIRMED);
    }

    /**
     * @return ProgressState
     */
    public static function partiallyAvailable(): self
    {
        return new self(ProgressStateMachine::PARTIALLY_AVAILABLE);
    }

    /**
     * @return ProgressState
     */
    public static function available(): self
    {
        return new self(ProgressStateMachine::AVAILABLE);
    }

    /**
     * @return ProgressState
     */
    public static function done(): self
    {
        return new self(ProgressStateMachine::DONE);
    }

    /**
     * @return ProgressState
     */
    public static function cancel(): self
    {
        return new self(ProgressStateMachine::CANCEL);
    }

    /**
     * @internal
     *
     * @return ProgressState
     */
    public function __construct($value)
    {
        $this->value = $value;
        $this->stateMachine = new ProgressStateMachine($this);
    }

    /**
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->value == ProgressStateMachine::DRAFT;
    }

    /**
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->value == ProgressStateMachine::CONFIRMED;
    }

    /**
     * @return bool
     */
    public function isPartiallyAvailable(): bool
    {
        return $this->value == ProgressStateMachine::PARTIALLY_AVAILABLE;
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->value == ProgressStateMachine::AVAILABLE;
    }

    /**
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->value == ProgressStateMachine::DONE;
    }

    /**
     * @return bool
     */
    public function isCancel(): bool
    {
        return $this->value == ProgressStateMachine::CANCELED;
    }

    /**
     * @return bool
     */
    public function isToDo(): bool
    {
        return !($this->isCancel() || $this->isDone() || $this->isDraft());
    }

    public function isInProgress(): bool
    {
        return $this->isPartiallyAvailable() || $this->isAvailable();
    }

    /**
     * @return ProgressState
     *
     * @throws DomainException
     */
    public function toDraft(): self
    {
        $this->stateMachine->apply('back_to_draft');

        return $this;
    }

    /**
     * @return ProgressState
     *
     * @throws DomainException
     */
    public function toConfirmed(): self
    {
        $this->stateMachine->apply('confirm');

        return $this;
    }

    /**
     * @return ProgressState
     *
     * @throws DomainException
     */
    public function toPartiallyAvailable(): self
    {
        $this->stateMachine->apply('partially_available');

        return $this;
    }

    /**
     * @return ProgressState
     *
     * @throws DomainException
     */
    public function toAvailable(): self
    {
        $this->stateMachine->apply('available');

        return $this;
    }

    /**
     * @return ProgressState
     *
     * @throws DomainException
     */
    public function toDone(): self
    {
        $this->stateMachine->apply('done');

        return $this;
    }

    /**
     * @return ProgressState
     *
     * @throws DomainException
     */
    public function toCancel(): self
    {
        $this->stateMachine->apply('cancel');

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }
}
