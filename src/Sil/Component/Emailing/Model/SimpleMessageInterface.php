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

interface SimpleMessageInterface
{
    /**
     * Gets from email address.
     *
     * @return EmailAddressInterface
     */
    public function getFrom(): EmailAddressInterface;

    /**
     * Gets to email addresses.
     *
     * @return array|EmailAddressInterface[]
     */
    public function getTo(): array;

    /**
     * Adds to email address.
     *
     * @param EmailAddressInterface $to
     *
     * @throws InvalidArgumentException
     */
    public function addTo(EmailAddressInterface $to): void;

    /**
     * Removes to email address.
     *
     * @param EmailAddressInterface $to
     *
     * @throws InvalidArgumentException
     */
    public function removeTo(EmailAddressInterface $to): void;

    /**
     * Gets cc email addresses.
     *
     * @return Collection|EmailAddressInterface[]
     */
    public function getCc(): array;

    /**
     * Adds cc email address.
     *
     * @param EmailAddressInterface $cc
     *
     * @throws InvalidArgumentException
     */
    public function addCc(EmailAddressInterface $cc): void;

    /**
     * Removes cc email address.
     *
     * @param EmailAddressInterface $cc
     *
     * @throws InvalidArgumentException
     */
    public function removeCc(EmailAddressInterface $cc): void;

    /**
     * Gets bcc email addresses.
     *
     * @return array|EmailAddressInterface[]
     */
    public function getBcc(): array;

    /**
     * Adds bcc email address.
     *
     * @param EmailAddressInterface $bcc
     *
     * @throws InvalidArgumentException
     */
    public function addBcc(EmailAddressInterface $bcc): void;

    /**
     * Removes bcc email address.
     *
     * @param EmailAddressInterface $bcc
     *
     * @throws InvalidArgumentException
     */
    public function removeBcc(EmailAddressInterface $bcc): void;
}
