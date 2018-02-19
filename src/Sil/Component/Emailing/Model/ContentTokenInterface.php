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

interface ContentTokenInterface
{
    /**
     * Gets the token name (througth token type).
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Gets the message that use the token.
     *
     * @return MessageInterface
     */
    public function getMessage(): MessageInterface;

    /**
     * Gets the token value.
     *
     * @return mixed|null
     */
    public function getValue();

    /**
     * Gets the token value.
     *
     * @param mixed $value
     *
     * @throws InvalidArgumentException
     */
    public function setValue($value): void;

    /**
     * Gets the token type.
     *
     * @return TokenTypeInterface
     */
    public function getTokenType(): TokenTypeInterface;

    /**
     * Gets the value as a string.
     *
     * @return string
     */
    public function getValueAsString(): string;
}
