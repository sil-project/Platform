<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\SeedBatchBundle\Entity;

use AppBundle\Entity\OuterExtension\LibrinfoSeedBatchBundle\CertificationExtension;
use Blast\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\BaseEntitiesBundle\Entity\Traits\Descriptible;
use Blast\BaseEntitiesBundle\Entity\Traits\Timestampable;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;

/**
 * Certification.
 */
class Certification
{
    use BaseEntity,
        OuterExtensible,
        CertificationExtension,
        Timestampable,
        Descriptible
    ;

    /**
     * @var string
     */
    private $code;

    /**
     * @var \DateTime
     */
    private $certificationDate;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $expiryDate;

    /**
     * @var string
     */
    private $url;

    /**
     * @var \Librinfo\SeedBatchBundle\Entity\CertificationType
     */
    private $certificationType;

    /**
     * @var \Librinfo\SeedBatchBundle\Entity\CertifyingBody
     */
    private $certifyingBody;

    /**
     * @var \Librinfo\SeedBatchBundle\Entity\Plot
     */
    private $plot;

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
     * @return Certification
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
     * Set certificationDate.
     *
     * @param \DateTime $certificationDate
     *
     * @return Certification
     */
    public function setCertificationDate($certificationDate)
    {
        $this->certificationDate = $certificationDate;

        return $this;
    }

    /**
     * Get certificationDate.
     *
     * @return \DateTime
     */
    public function getCertificationDate()
    {
        return $this->certificationDate;
    }

    /**
     * Set startDate.
     *
     * @param \DateTime $startDate
     *
     * @return Certification
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate.
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set expiryDate.
     *
     * @param \DateTime $expiryDate
     *
     * @return Certification
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * Get expiryDate.
     *
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return Certification
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
     * Set certificationType.
     *
     * @param \Librinfo\SeedBatchBundle\Entity\CertificationType $certificationType
     *
     * @return Certification
     */
    public function setCertificationType(CertificationType $certificationType = null)
    {
        $this->certificationType = $certificationType;

        return $this;
    }

    /**
     * Get certificationType.
     *
     * @return \Librinfo\SeedBatchBundle\Entity\CertificationType
     */
    public function getCertificationType()
    {
        return $this->certificationType;
    }

    /**
     * Set plot.
     *
     * @param \Librinfo\SeedBatchBundle\Entity\Plot $plot
     *
     * @return Certification
     */
    public function setPlot(\Librinfo\SeedBatchBundle\Entity\Plot $plot = null)
    {
        $this->plot = $plot;

        return $this;
    }

    /**
     * Get plot.
     *
     * @return \Librinfo\SeedBatchBundle\Entity\Plot
     */
    public function getPlot()
    {
        return $this->plot;
    }

    /**
     * Set certifyingBody.
     *
     * @param \Librinfo\SeedBatchBundle\Entity\CertifyingBody $certifyingBody
     *
     * @return Certification
     */
    public function setCertifyingBody(\Librinfo\SeedBatchBundle\Entity\CertifyingBody $certifyingBody = null)
    {
        $this->certifyingBody = $certifyingBody;

        return $this;
    }

    /**
     * Get certifyingBody.
     *
     * @return \Librinfo\SeedBatchBundle\Entity\CertifyingBody
     */
    public function getCertifyingBody()
    {
        return $this->certifyingBody;
    }
}
