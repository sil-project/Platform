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

use InvalidArgumentException;
use Sil\Component\Emailing\StateMachine\MessageStateMachine;

class MessageState implements MessageStateInterface
{
    const DRAFT = 'draft';
    const VALIDATED = 'validated';
    const SENT = 'sent';
    const DELETED = 'deleted';

    /**
     * The state value.
     *
     * @var string
     */
    protected $value;

    /**
     * @var MessageStateMachine
     */
    protected $stateMachine;

    /**
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $value)
    {
        $this->setValue($value);
        $this->stateMachine = new MessageStateMachine($this);
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
            'SENT'      => static::SENT,
            'DELETED'   => static::DELETED,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function draft(): MessageStateInterface
    {
        return new self(static::DRAFT);
    }

    /**
     * {@inheritdoc}
     */
    public static function validated(): MessageStateInterface
    {
        return new self(static::VALIDATED);
    }

    /**
     * {@inheritdoc}
     */
    public static function sent(): MessageStateInterface
    {
        return new self(static::SENT);
    }

    /**
     * {@inheritdoc}
     */
    public static function deleted(): MessageStateInterface
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
    public function isSent(): bool
    {
        return $this->value === static::SENT;
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
    public function toDelete(): MessageStateInterface
    {
        $this->stateMachine->apply('delete');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toValidate(): MessageStateInterface
    {
        $this->stateMachine->apply('validate');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toSent(): MessageStateInterface
    {
        $this->stateMachine->apply('sent');

        return $this;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
