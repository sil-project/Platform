<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\MediaBundle\Entity;

trait HasImageTrait
{
    /**
     * @var Image
     */
    private $image;

    /**
     * Set image.
     *
     * @param File $image
     *
     * @return self
     */
    public function setImage(File $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return File
     */
    public function getImage()
    {
        return $this->image;
    }

    public function setLibrinfoFile(File $file)
    {
        $this->setImage($file);
    }

    public function getLibrinfoFile()
    {
        return $this->getImage();
    }
}
