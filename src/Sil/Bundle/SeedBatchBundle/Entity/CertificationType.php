<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\SeedBatchBundle\Entity;

use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Descriptible;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Nameable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Timestampable;
use Sil\Bundle\MediaBundle\Entity\File;

/**
 * CertificationType.
 */
class CertificationType
{
    use BaseEntity,
        Nameable,
        Timestampable,
        Descriptible;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var File
     */
    protected $logo = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->initCollections();
    }

    // implementation of __clone for duplication
    public function __clone()
    {
        $this->id = null;
        $this->code = null;
        $this->logo = null;
        $this->initCollections();
    }

    public function initCollections()
    {
    }

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return CertificationType
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set logo.
     *
     * @param \Sil\Bundle\MediaBundle\Entity\File $logo
     *
     * @return CertificationType
     */
    public function setLogo($logo = null)
    {
        // @TODO: Ugly hack to avoid $form->handleRequest() changing this value from null to empty array
        if (is_array($logo)) {
            $logo = null;
        }

        $this->logo = $logo;

        return $this;
    }

    /**
     * alias for SilMediaBundle/CRUDController::handleFiles().
     *
     * @param \Sil\Bundle\MediaBundle\Entity\File $logo
     */
    public function setLibrinfoFile(File $logo = null)
    {
        $this->setLogo($logo);
    }

    /**
     * Get logo.
     *
     * @return \Sil\Bundle\MediaBundle\Entity\File
     */
    public function getLogo()
    {
        return $this->logo;
    }

    public function getLibrinfoFile()
    {
        return $this->getLogo();
    }
}
