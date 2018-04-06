<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\UserBundle\Entity\Traits;

//use Sil\Bundle\UserBundle\Entity\UserInterface;

/* @todo: remove this or not or use this */
trait Traceable
{
    /**
     * @var UserInterface
     */
    protected $createdBy = null;

    /**
     * @var UserInterface
     */
    protected $updatedBy = null;

    /**
     * @return UserInterface
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param UserInterface $createdBy
     *
     * @return Traceable
     */
    public function setCreatedBy(UserInterface $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param UserInterface $updatedBy
     *
     * @return Traceable
     */
    public function setUpdatedBy(UserInterface $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }
}
