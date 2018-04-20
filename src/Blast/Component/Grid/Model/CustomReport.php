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

use Blast\Component\Resource\Model\ResourceInterface;

class CustomReport implements CustomReportInterface, ResourceInterface
{
    /**
     * @var string
     */
    protected $gridName;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $public = false;

    /**
     * The owner of the report.
     *
     * @var CustomReportOwnerInterface
     */
    protected $owner;

    public function __construct(string $gridName, string $uri, string $name, ?bool $public = false)
    {
        $this->gridName = $gridName;
        $this->uri = $uri;
        $this->name = $name;
        $this->public = $public;
    }

    /**
     * Get the value of gridName.
     *
     * @return string
     */
    public function getGridName(): string
    {
        return $this->gridName;
    }

    /**
     * {@inheritdoc}
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function isPublic(): bool
    {
        return $this->public;
    }

    /**
     * {@inheritdoc}
     */
    public function setPublic(bool $public): void
    {
        $this->public = $public;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwner(): ?CustomReportOwnerInterface
    {
        return $this->owner;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwner(?CustomReportOwnerInterface $owner): void
    {
        $this->owner = $owner;
    }
}
