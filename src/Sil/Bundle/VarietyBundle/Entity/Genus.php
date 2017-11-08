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
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Genus.
 */
class Genus implements GenusInterface
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
     * @var Family
     */
    protected $family;

    /**
     * @var Collection
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
        $this->specieses = new ArrayCollection();
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
     * @param Family $family
     *
     * @return Genus
     */
    public function setFamily(Family $family = null)
    {
        $this->family = $family;

        return $this;
    }

    /**
     * Get family.
     *
     * @return Family
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * Alias for addSpecies.
     *
     * @param Species $species
     *
     * @return Genus
     */
    public function addSpeciese(Species $species)
    {
        $species->setGenus($this);
        $this->specieses->add($species);

        return $this;
    }

    /**
     * Add species.
     *
     * @param Species $species
     *
     * @return Genus
     */
    public function addSpecies(Species $species)
    {
        $species->setGenus($this);
        $this->specieses->add($species);

        return $this;
    }

    /**
     * Remove speciese.
     *
     * @param Species $species
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeSpecies(Species $species)
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
