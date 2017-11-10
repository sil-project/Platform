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

use Doctrine\Common\Collections\ArrayCollection;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Addressable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Descriptible;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Loggable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Timestampable;

/**
 * CertifyingBody.
 */
class CertifyingBody
{
    use BaseEntity,
        Addressable,
        Timestampable,
        Loggable,
        Descriptible;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $certifications;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->country = 'FR';
        $this->initCollections();
    }

    // implementation of __clone for duplication
    public function __clone()
    {
        $this->id = null;
        $this->code = null;
        $this->initCollections();
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    public function initCollections()
    {
        $this->certifications = new ArrayCollection();
    }

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return Plot
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
     * Set url.
     *
     * @param string $url
     *
     * @return CertifyingBody
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Add certifications.
     *
     * @param \Sil\Bundle\SeedBatchBundle\Entity\Certification $certifications
     *
     * @return Plot
     */
    public function addCertification(\Sil\Bundle\SeedBatchBundle\Entity\Certification $certifications)
    {
        $this->certifications[] = $certifications;

        return $this;
    }

    /**
     * Remove certification.
     *
     * @param \Sil\Bundle\SeedBatchBundle\Entity\Certification $certification
     */
    public function removeCertification(\Sil\Bundle\SeedBatchBundle\Entity\Certification $certification)
    {
        $this->certifications->removeElement($certification);
    }

    /**
     * Get certifications.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCertifications()
    {
        return $this->certifications;
    }
}
