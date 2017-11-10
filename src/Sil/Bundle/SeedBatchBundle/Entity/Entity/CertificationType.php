<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\SeedBatchBundle\Entity;

use Blast\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\BaseEntitiesBundle\Entity\Traits\Descriptible;
use Blast\BaseEntitiesBundle\Entity\Traits\Nameable;
use Blast\BaseEntitiesBundle\Entity\Traits\Timestampable;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use Librinfo\MediaBundle\Entity\File;
use AppBundle\Entity\OuterExtension\LibrinfoSeedBatchBundle\CertificationTypeExtension;

/**
 * CertificationType.
 */
class CertificationType
{
    use BaseEntity,
        Nameable,
        OuterExtensible,
        CertificationTypeExtension,
        Timestampable,
        Descriptible;

    /**
     * @var string
     */
    private $code;

    /**
     * @var File
     */
    private $logo = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->initCollections();
        $this->initOuterExtendedClasses();
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
     * @param \Librinfo\MediaBundle\Entity\File $logo
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
     * alias for LibrinfoMediaBundle/CRUDController::handleFiles().
     *
     * @param \Librinfo\MediaBundle\Entity\File $logo
     */
    public function setLibrinfoFile(File $logo = null)
    {
        $this->setLogo($logo);
    }

    /**
     * Get logo.
     *
     * @return \Librinfo\MediaBundle\Entity\File
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
