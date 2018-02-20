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

class ContentToken implements ContentTokenInterface
{
    /**
     * The value of the token.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Type of token.
     *
     * @var ContentTokenTypeInterface
     */
    protected $tokenType;

    /**
     * The message that use the token.
     *
     * @var MessageInterface
     */
    protected $message;

    public function __construct(MessageInterface $message, ContentTokenTypeInterface $tokenType)
    {
        $this->tokenType = $tokenType;
        $this->message = $message;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->getTokenType()->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage(): MessageInterface
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value): void
    {
        $typeIsValid = false;

        switch ($this->getTokenType()->getDataType()->getValue()) {
            case ContentTokenDataType::TYPE_BOOLEAN:
                $typeIsValid = is_bool($value);
                break;

            case ContentTokenDataType::TYPE_DATE:
            case ContentTokenDataType::TYPE_DATETIME:
                $typeIsValid = get_class($value) === 'DateTime';
                break;

            case ContentTokenDataType::TYPE_STRING:
                $typeIsValid = is_string($value);
                break;

            case ContentTokenDataType::TYPE_INTEGER:
                $typeIsValid = is_int($value);
                break;

            case ContentTokenDataType::TYPE_FLOAT:
                $typeIsValid = is_float($value);
                break;

            default:
                throw new InvalidArgumentException(sprintf('The type of the value (%s) of ContentToken « %s » is not managed', $this->getName(), gettype($value)));
        }

        if (!$typeIsValid) {
            throw new InvalidArgumentException(sprintf('Value of ContentToken « %s » must be of type « %s », « %s » given', $this->getName(), $this->getTokenType()->getDataType(), gettype($value)));
        }

        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenType(): ContentTokenTypeInterface
    {
        return $this->tokenType;
    }

    /**
     * {@inheritdoc}
     */
    public function getValueAsString(): string
    {
        $stringValue = '';

        switch ($this->getTokenType()->getDataType()->getValue()) {
            case ContentTokenDataType::TYPE_BOOLEAN:
                $stringValue = $this->value === true ? 'Yes' : 'No';
                break;

            case ContentTokenDataType::TYPE_DATE:
                $stringValue = $this->value->format('Y-m-d');
                break;

            case ContentTokenDataType::TYPE_DATETIME:
                $stringValue = $this->value->format('Y-m-d H:i:s');
                break;

            case ContentTokenDataType::TYPE_STRING:
            case ContentTokenDataType::TYPE_INTEGER:
            case ContentTokenDataType::TYPE_FLOAT:
            default:
                $stringValue = (string) $this->value;
        }

        return $stringValue;
    }
}
