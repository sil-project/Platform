<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\VarietiesBundle\Entity;

use AppBundle\Entity\OuterExtension\LibrinfoVarietiesBundle\GenusExtension;
use Blast\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\BaseEntitiesBundle\Entity\Traits\Descriptible;
use Blast\BaseEntitiesBundle\Entity\Traits\Nameable;
use Blast\BaseEntitiesBundle\Entity\Traits\Timestampable;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;

/**
 * Genus.
 */
class Genus
{
    use BaseEntity,
        Nameable,
        Timestampable,
        Descriptible,
        OuterExtensible,
        GenusExtension
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
     * @var \Librinfo\VarietiesBundle\Entity\Family
     */
    private $family;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $specieses;

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
     * @param \Librinfo\VarietiesBundle\Entity\Family $family
     *
     * @return Genus
     */
    public function setFamily(\Librinfo\VarietiesBundle\Entity\Family $family = null)
    {
        $this->family = $family;

        return $this;
    }

    /**
     * Get family.
     *
     * @return \Librinfo\VarietiesBundle\Entity\Family
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * Alias for addSpecies.
     *
     * @param \Librinfo\VarietiesBundle\Entity\Species $species
     *
     * @return \Librinfo\VarietiesBundle\Entity\Genus
     */
    public function addSpeciese(\Librinfo\VarietiesBundle\Entity\Species $species)
    {
        $species->setGenus($this);
        $this->specieses->add($species);

        return $this;
    }

    /**
     * Add species.
     *
     * @param \Librinfo\VarietiesBundle\Entity\Species $species
     *
     * @return Genus
     */
    public function addSpecies(\Librinfo\VarietiesBundle\Entity\Species $species)
    {
        $species->setGenus($this);
        $this->specieses->add($species);

        return $this;
    }

    /**
     * Remove speciese.
     *
     * @param \Librinfo\VarietiesBundle\Entity\Species $species
     *
     * @return bool tRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeSpecies(\Librinfo\VarietiesBundle\Entity\Species $species)
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
