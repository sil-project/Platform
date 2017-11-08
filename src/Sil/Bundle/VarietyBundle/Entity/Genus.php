<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\VarietyBundle\Entity;

use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Descriptible;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Nameable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Timestampable;

/**
 * Genus.
 */
class Genus
{
    use BaseEntity,
        Nameable,
        Timestampable,
        Descriptible
    ;

    /**
     * @var string
     */
    protected $latin_name;

    /**
     * @var string
     */
    protected $alias;

    /**
     * @var \Sil\Bundle\VarietyBundle\Entity\Family
     */
    protected $family;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $specieses;

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
        $this->specieses = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set latinName.
     *
     * @param string $latinName
     *
     * @return Genus
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
     * @return Genus
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
     * Set family.
     *
     * @param \Sil\Bundle\VarietyBundle\Entity\Family $family
     *
     * @return Genus
     */
    public function setFamily(\Sil\Bundle\VarietyBundle\Entity\Family $family = null)
    {
        $this->family = $family;

        return $this;
    }

    /**
     * Get family.
     *
     * @return \Sil\Bundle\VarietyBundle\Entity\Family
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * Alias for addSpecies.
     *
     * @param \Sil\Bundle\VarietyBundle\Entity\Species $species
     *
     * @return \Sil\Bundle\VarietyBundle\Entity\Genus
     */
    public function addSpeciese(\Sil\Bundle\VarietyBundle\Entity\Species $species)
    {
        $species->setGenus($this);
        $this->specieses->add($species);

        return $this;
    }

    /**
     * Add species.
     *
     * @param \Sil\Bundle\VarietyBundle\Entity\Species $species
     *
     * @return Genus
     */
    public function addSpecies(\Sil\Bundle\VarietyBundle\Entity\Species $species)
    {
        $species->setGenus($this);
        $this->specieses->add($species);

        return $this;
    }

    /**
     * Remove speciese.
     *
     * @param \Sil\Bundle\VarietyBundle\Entity\Species $species
     *
     * @return bool tRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeSpecies(\Sil\Bundle\VarietyBundle\Entity\Species $species)
    {
        return $this->specieses->removeElement($species);
    }

    /**
     * Get specieses.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpecieses()
    {
        return $this->specieses;
    }
}
