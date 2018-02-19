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

interface MessageTemplateInterface
{
    /**
     * Gets the message name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Gets the content of the message.
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Lists content tokens for the message.
     *
     * @return array|ContentTokenTypeInterface|]
     */
    public function getTokenTypes(): array;

    /**
     * Adds a token type to the template.
     *
     * @param ContentTokenTypeInterface $token
     *
     * @throws InvalidArgumentException
     */
    public function addTokenType(ContentTokenTypeInterface $token): void;

    /**
     * Removes a content token type from this template.
     *
     * @param ContentTokenTypeInterface $token
     *
     * @throws InvalidArgumentException
     */
    public function removeTokenType(ContentTokenTypeInterface $token): void;
}
