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

namespace Sil\Bundle\VarietyBundle\Entity;

use AppBundle\Entity\OuterExtension\SilVarietyBundle\FamilyExtension;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Descriptible;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Nameable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Timestampable;
use Blast\Bundle\OuterExtensionBundle\Entity\Traits\OuterExtensible;

/**
 * Family.
 */
class Family
{
    use BaseEntity,
        Nameable,
        Timestampable,
        Descriptible,
        OuterExtensible,
        FamilyExtension
    ;

    /**
     * @var string
     */
    private $latin_name;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $genuses;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->initCollections();
    }

    public function __clone()
    {
        $this->id = null;
        $this->initCollections();
    }

    public function initCollections()
    {
        $this->genuses = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set latinName.
     *
     * @param string $latinName
     *
     * @return Family
     */
    public function setLatinName($latinName)
    {
        $this->latin_name = $latinName;

        return $this;
    }

    /**
     * Get latinName.
     *
     * @return string
     */
    public function getLatinName()
    {
        return $this->latin_name;
    }

    /**
     * Set alias.
     *
     * @param string $alias
     *
     * @return Family
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias.
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Add genus.
     *
     * @param \Sil\Bundle\VarietyBundle\Entity\Genus $genus
     *
     * @return Family
     */
    public function addGenus(\Sil\Bundle\VarietyBundle\Entity\Genus $genus)
    {
        $genus->setFamily($this);
        $this->genuses->add($genus);

        return $this;
    }

    /**
     * Remove genus.
     *
     * @param \Sil\Bundle\VarietyBundle\Entity\Genus $genus
     *
     * @return bool tRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeGenus(\Sil\Bundle\VarietyBundle\Entity\Genus $genus)
    {
        return $this->genuses->removeElement($genus);
    }

    /**
     * Get genuses.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGenuses()
    {
        return $this->genuses;
    }
}
