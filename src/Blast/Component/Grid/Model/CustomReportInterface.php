<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\Grid\Model;

interface CustomReportInterface
{
    /**
     * Get the grid name.
     *
     * @return string
     */
    public function getGridName(): string;

    /**
     * Get the value of URI.
     *
     * @return string
     */
    public function getUri(): string;

    /**
     * Get the value of name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set the value of name.
     *
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * Get the value of public.
     *
     * @return bool
     */
    public function isPublic(): bool;

    /**
     * Set the value of public.
     *
     * @param bool $public
     */
    public function setPublic(bool $public): void;

    /**
     * Get the owner of the report.
     *
     * @return CustomReportOwnerInterface
     */
    public function getOwner(): ?CustomReportOwnerInterface;

    /**
     * Set the owner of the report.
     *
     * @param CustomReportOwnerInterface $owner The owner of the report
     */
    public function setOwner(?CustomReportOwnerInterface $owner): void;
}
