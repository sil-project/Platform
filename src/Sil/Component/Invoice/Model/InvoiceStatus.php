<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Invoice\Model;

use Sil\Component\Invoice\Exception\InvoiceStatusChangeException;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class InvoiceStatus
{
    const STATUS_DRAFT = 0;
    const STATUS_VALIDATED = 1;
    const STATUS_PAID = 2;

    /**
     * value.
     *
     * @var int
     */
    protected $value;

    /**
     * Force draft state on creation.
     */
    public function __construct()
    {
        $this->value = self::STATUS_DRAFT;
    }

    /**
     * Update status from draft to validated.
     */
    public function beValidated(): void
    {
        if ($this->value !== self::STATUS_DRAFT) {
            throw new InvoiceStatusChangeException('Cannot change invoice status from paid back to validated');
        }

        $this->value = self::STATUS_VALIDATED;
    }

    /**
     * Update status from validated to paid.
     */
    public function bePaid(): void
    {
        if ($this->value !== self::STATUS_VALIDATED) {
            throw new InvoiceStatusChangeException('Cannot change invoice status from draft to paid');
        }

        $this->value = self::STATUS_PAID;
    }

    /**
     * @return bool whether the status is draft
     */
    public function isDraft(): bool
    {
        return $this->value === self::STATUS_DRAFT;
    }

    /**
     * @return bool whether the status is validated
     */
    public function isValidated(): bool
    {
        return $this->value === self::STATUS_VALIDATED;
    }

    /**
     * @return bool whether the status is paid
     */
    public function isPaid(): bool
    {
        return $this->value === self::STATUS_PAID;
    }
}
