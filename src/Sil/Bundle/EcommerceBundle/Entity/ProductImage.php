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

namespace Librinfo\EcommerceBundle\Entity;

/* @todo reference to AppBundle should be removed */
use AppBundle\Entity\OuterExtension\LibrinfoEcommerceBundle\ProductImageExtension;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use Sylius\Component\Core\Model\ProductImage as BaseProductImage;
use Librinfo\MediaBundle\Entity\File;
use SplFileInfo;

class ProductImage extends BaseProductImage
{
    const TYPE_COVER = 'main';
    const TYPE_THUMBNAIL = 'thumbnail';

    use OuterExtensible,
    ProductImageExtension;

    /**
     * @var string
     */
    protected $type = self::TYPE_THUMBNAIL;

    /**
     * @var File
     */
    protected $realFile;

    public function __construct()
    {
        parent::__construct();
    }

    public function getName()
    {
        return $this->realFile === null ? null : $this->realFile->getName();
    }

    public function getBase64File()
    {
        return $this->realFile === null ? null : $this->realFile->getBase64File();
    }

    public function getMimeType()
    {
        return $this->realFile === null ? null : $this->realFile->getMimeType();
    }

    public function getRealFile()
    {
        return $this->realFile;
    }

    public function setRealFile(File $file)
    {
        $this->realFile = $file;

        return $this;
    }

    public function getFile(): ?SplFileInfo
    {
        return parent::getFile();
    }

    public function setFile(?SplFileInfo $file): void
    {
        // Shouldn't be used
        parent::setFile($file);
    }

    public function setOwner($owner): void
    {
        parent::setOwner($owner);
    }

    public function setPath(?string $path): void
    {
        parent::setPath($path);

        if ($this->realFile !== null) {
            $this->path = md5($this->realFile->getId());
        }
    }

    public function setType(?string $type): void
    {
        parent::setType($type);
    }

    public function __toString(): string
    {
        return (string) $this->getPath();
    }
}
