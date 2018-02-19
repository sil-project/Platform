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
use Blast\Component\Resource\Model\ResourceInterface;

class EmailAddress implements EmailAddressInterface, ResourceInterface
{
    /**
     * The email address as string.
     *
     * @var string
     */
    protected $value;

    /**
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $value)
    {
        if (!$this->isValid($value)) {
            throw new InvalidArgumentException(sprintf('The string « %s » is not a valid email address', $value));
        }

        $this->value = $value;
    }

    /**
     * Validate if given string is an email.
     *
     * @param string $value
     */
    public function isValid(?string $value = null): bool
    {
        if ($value === null) {
            $value = $this->value;
        }

        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Gets the email as string.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
