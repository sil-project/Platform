<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\UserBundle\Entity\Traits;

/* @todo: remove this or not or use this */

trait Ownable
{
    /**
     * @var UserInterface
     */
    protected $owner = null;

    /**
     * @return UserInterface
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param UserInterface $owner
     *
     * @return self
     */
    public function setOwner(UserInterface $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }
}
