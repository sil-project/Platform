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

interface MessageInterface
{
    /**
     * Gets the message title.
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Sets the message title.
     *
     * @param string $title
     */
    public function setTitle(string $title): void;

    /**
     * Gets the content of the message.
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Sets the message content.
     *
     * @param string $content
     */
    public function setContent(string $content): void;

    /**
     * Retreives the message template.
     *
     * @return MessageTemplateInterface
     */
    public function getTemplate(): ?MessageTemplateInterface;

    /**
     * Sets the message template.
     *
     * @param MessageTemplateInterface $template
     */
    public function setTemplate(?MessageTemplateInterface $template): void;

    /**
     * Lists content tokens for the message.
     *
     * @return array|ContentTokenInterface[]
     */
    public function getTokens(): array;

    /**
     * Adds a content token to the message.
     *
     * @param ContentTokenInterface $token
     *
     * @throws InvalidArgumentException
     */
    public function addToken(ContentTokenInterface $token): void;

    /**
     * Removes a content token from this message.
     *
     * @param ContentTokenInterface $token
     *
     * @throws InvalidArgumentException
     */
    public function removeToken(ContentTokenInterface $token): void;

    /**
     * Removes all tokens for current message.
     */
    public function clearTokens(): void;

    /**
     * Gets a list of attachments.
     *
     * @return array|AttachmentInterface[]
     */
    public function getAttachments(): array;

    /**
     * Adds an attachment to the message.
     *
     * @param AttachmentInterface $attachment
     *
     * @throws InvalidArgumentException
     */
    public function addAttachment(AttachmentInterface $attachment): void;

    /**
     * Removes an attachment to the message.
     *
     * @param AttachmentInterface $attachment
     *
     * @throws InvalidArgumentException
     */
    public function removeAttachment(AttachmentInterface $attachment): void;
}
