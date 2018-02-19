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

interface EmailAddressInterface
{
    /**
     * Validate if given string is an email.
     *
     * @param string $value
     */
    public function isValid(?string $value = null): bool;

    /**
     * Gets the email as string.
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * Gets email address as string.
     *
     * @return string
     */
    public function __toString(): string;
}
