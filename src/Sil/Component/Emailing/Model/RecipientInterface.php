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

interface RecipientInterface
{
    /**
     * Instanciate a recipient from an email as string.
     *
     * @param string $email
     *
     * @return self
     */
    public static function createFromEmailAsString(string $email): self;

    /**
     * Returns the valid state of recipient.
     *
     * @return bool
     */
    public function isValid(): bool;

    /**
     * Sets the valid state of recipient.
     *
     * @param bool $valid
     */
    public function setValid(bool $valid): void;

    /**
     * Gets recipient's email.
     *
     * @return EmailAddressInterface
     */
    public function getEmail(): EmailAddressInterface;
}
