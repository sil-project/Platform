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

namespace Librinfo\MediaBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Blast\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use Blast\BaseEntitiesBundle\Entity\Traits\Jsonable;
use AppBundle\Entity\OuterExtension\LibrinfoMediaBundle\FileExtension;

/**
 * File.
 */
class File implements \JsonSerializable
{
    use BaseEntity,
        OuterExtensible,
        FileExtension,
        Jsonable;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var float
     */
    private $size;

    /**
     * @var UploadedFile file
     */
    private $file;

    /**
     * @var object
     */
    private $parent;

    /**
     * @var bool
     */
    private $owned;

    public function __construct()
    {
        $this->initOuterExtendedClasses();
    }

    /**
     * @param UploadedFile $file
     *
     * @return \Librinfo\MediaBundle\Entity\File
     */
    public function setFile($file = null)
    {
        if ($file instanceof UploadedFile) {
            $this->file = base64_encode(file_get_contents($file));
        } else {
            $this->file = $file;
        }

        return $this;
    }

    /**
     * @return type
     */
    public function getFile()
    {
        return base64_decode($this->file);
    }

    /**
     * @return type
     */
    public function getBase64File()
    {
        return $this->file;
    }

    /**
     * Set parent.
     *
     * @param string $parent
     *
     * @return File
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return File
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set mimeType.
     *
     * @param string $mimeType
     *
     * @return File
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType.
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set size.
     *
     * @param float $size
     *
     * @return File
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size.
     *
     * @return float
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set owned.
     *
     * @param string $owned
     *
     * @return File
     */
    public function setOwned($owned)
    {
        $this->owned = $owned;

        return $this;
    }

    /**
     * Get owned.
     *
     * @return string
     */
    public function getOwned()
    {
        return $this->owned;
    }

    /**
     * isOwned.
     *
     * @return bool
     */
    public function isOwned(): bool
    {
        return (bool) $this->owned;
    }

    public function __clone()
    {
        $this->id = null;
        $this->owned = false;
        $this->initOuterExtendedClasses();
    }
}
