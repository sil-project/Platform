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

trait Lockable
{
    /**
     * @var bool
     */
    protected $locked = false;

    /**
     * @return bool
     */
    public function isLocked(): bool
    {
        return (bool) $this->locked;
    }
}
