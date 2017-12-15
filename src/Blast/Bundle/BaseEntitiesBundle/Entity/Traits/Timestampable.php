<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Entity\Traits;

use DateTime;
use DateTimeInterface;

trait Timestampable
{
    /**
     * @var DateTime
     */
    protected $createdAt = null;

    /**
     * @var DateTime
     */
    protected $updatedAt = null;

    /**
     * @return DateTime
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     *
     * @return Timestampable
     */
    public function setCreatedAt(?DateTimeInterface $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $lastUpdatedAt
     *
     * @return Timestampable
     */
    public function setUpdatedAt(?DateTimeInterface $lastUpdatedAt)
    {
        $this->updatedAt = $lastUpdatedAt;

        return $this;
    }
}
