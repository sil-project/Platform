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

interface MailingListInterface
{
    /**
     * Gets the name of list.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Gets the description of the list if set.
     *
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * Sets the description of the list.
     *
     * @param ?string $description
     */
    public function setDescription(?string $description): void;

    /**
     * Enable or disable the list.
     *
     * @param bool $enabled
     *
     * @throws DomainException
     */
    public function setEnabled(bool $enabled): void;

    /**
     * Returns enabled state of the list.
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Gets the collection or recipient.
     *
     * @return array|RecipientInterface[]
     */
    public function getRecipients(): array;

    /**
     * Adds a recipient to the list.
     *
     * @param RecipientInterface $recipient
     *
     * @throws InvalidArgumentException
     */
    public function addRecipient(RecipientInterface $recipient): void;

    /**
     * Removes a recipient from the list.
     *
     * @param RecipientInterface $recipient
     *
     * @throws InvalidArgumentException
     */
    public function removeRecipient(RecipientInterface $recipient): void;
}
