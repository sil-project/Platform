<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\MediaBundle\Entity\Association;

use Sil\Bundle\MediaBundle\Entity\FileInterface;

trait HasImageTrait
{
    /**
     * @var FileInterface
     */
    private $image;

    /**
     * Set image.
     *
     * @param FileInterface $image
     *
     * @return self
     */
    public function setImage(FileInterface $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return FileInterface
     */
    public function getImage()
    {
        return $this->image;
    }

    public function setLibrinfoFile(FileInterface $file)
    {
        $this->setImage($file);
    }

    public function getLibrinfoFile()
    {
        return $this->getImage();
    }
}
