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

use InvalidArgumentException;
use DomainException;
use Sil\Component\Order\StateMachine\OrderStateMachine;

class OrderState implements OrderStateInterface
{
    const DRAFT = 'draft';
    const VALIDATED = 'validated';
    const CANCELLED = 'cancelled';
    const FULFILLED = 'fulfilled';
    const DELETED = 'deleted';

    /**
     * @var string
     */
    protected $value;

    /**
     * @var OrderStateMachine
     */
    protected $stateMachine;

    /**
     * @internal
     *
     * @param string $state
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $state)
    {
        $this->setValue($state);
        $this->stateMachine = new OrderStateMachine($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @internal
     *
     * @param string $value
     */
    public function setValue(string $value): void
    {
        if (!in_array($value, static::getStates())) {
            throw new InvalidArgumentException(sprintf('The state %s is not a valid state, valids are : %s', $value, implode(', ', static::getStates())));
        }
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public static function getStates(): array
    {
        return [
            'DRAFT'     => static::DRAFT,
            'VALIDATED' => static::VALIDATED,
            'CANCELLED' => static::CANCELLED,
            'FULFILLED' => static::FULFILLED,
            'DELETED'   => static::DELETED,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function draft(): OrderStateInterface
    {
        return new self(static::DRAFT);
    }

    /**
     * {@inheritdoc}
     */
    public static function validated(): OrderStateInterface
    {
        return new self(static::VALIDATED);
    }

    /**
     * {@inheritdoc}
     */
    public static function cancelled(): OrderStateInterface
    {
        return new self(static::CANCELLED);
    }

    /**
     * {@inheritdoc}
     */
    public static function fulfilled(): OrderStateInterface
    {
        return new self(static::FULFILLED);
    }

    /**
     * {@inheritdoc}
     */
    public static function deleted(): OrderStateInterface
    {
        return new self(static::DELETED);
    }

    /**
     * {@inheritdoc}
     */
    public function isDraft(): bool
    {
        return $this->value === static::DRAFT;
    }

    /**
     * {@inheritdoc}
     */
    public function isValidated(): bool
    {
        return $this->value === static::VALIDATED;
    }

    /**
     * {@inheritdoc}
     */
    public function isCancelled(): bool
    {
        return $this->value === static::CANCELLED;
    }

    /**
     * {@inheritdoc}
     */
    public function isFulfilled(): bool
    {
        return $this->value === static::FULFILLED;
    }

    /**
     * {@inheritdoc}
     */
    public function isDeleted(): bool
    {
        return $this->value === static::DELETED;
    }

    /**
     * {@inheritdoc}
     */
    public function toDelete(): OrderStateInterface
    {
        $this->stateMachine->apply('delete');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toValidate(): OrderStateInterface
    {
        $this->stateMachine->apply('validate');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toFulfill(): OrderStateInterface
    {
        $this->stateMachine->apply('fulfill');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toCancel(): OrderStateInterface
    {
        $this->stateMachine->apply('cancel');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function allowDataChanges(): void
    {
        if ($this->value !== self::DRAFT) {
            throw new DomainException(sprintf('The Order\'s data cannot be changed when in state %s', $this->value));
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
