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

use Blast\Component\Resource\Model\ResourceInterface;

class Recipient implements RecipientInterface, ResourceInterface
{
    /**
     * The recipient is valid (e.g.: no bounces).
     *
     * @var bool
     */
    protected $valid = true;

    /**
     * The recipient's email.
     *
     * @var EmailAddressInterface
     */
    protected $email;

    /**
     * @param EmailAddressInterface $email
     */
    public function __construct(EmailAddressInterface $email)
    {
        $this->email = $email;
    }

    /**
     * {@inheritdoc}
     */
    public static function createFromEmailAsString(string $email): RecipientInterface
    {
        $email = new EmailAddress($email);

        return new self($email);
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * {@inheritdoc}
     */
    public function setValid(bool $valid): void
    {
        $this->valid = $valid;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail(): EmailAddressInterface
    {
        return $this->email;
    }
}
