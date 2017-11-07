<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\EcommerceBundle\Entity\OuterExtension\LibrinfoMediaBundle;

use Librinfo\EcommerceBundle\Entity\Product;

trait FileExtension
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var Product
     */
    private $owner;

    /**
     * @var string
     */
    private $type;

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner(Product $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
