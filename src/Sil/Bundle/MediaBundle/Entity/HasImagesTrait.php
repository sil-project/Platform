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

use Doctrine\Common\Collections\Collection;

trait HasImagesTrait
{
    /**
     * @var Collection
     */
    private $images;

    /**
     * Add image.
     *
     * @param object $image
     *
     * @return self
     */
    public function addImage(File $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image.
     *
     * @param object $image
     *
     * @return bool tRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeImage(File $image)
    {
        return $this->images->removeElement($image);
    }

    /**
     * Get images.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set images.
     *
     * @return self
     */
    public function setImages(array $images)
    {
        $this->images = $images;

        return $this;
    }

    public function addLibrinfoFile(File $file)
    {
        $this->addImage($file);
    }

    public function removeLibrinfoFile(File $file)
    {
        $this->removeImage($file);
    }

    public function getLibrinfoFiles()
    {
        return $this->getImages();
    }
}
