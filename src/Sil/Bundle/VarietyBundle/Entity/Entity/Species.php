<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\VarietiesBundle\Entity;

use AppBundle\Entity\OuterExtension\LibrinfoVarietiesBundle\SpeciesExtension;
use Blast\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\BaseEntitiesBundle\Entity\Traits\Descriptible;
use Blast\BaseEntitiesBundle\Entity\Traits\Jsonable;
use Blast\BaseEntitiesBundle\Entity\Traits\Nameable;
use Blast\BaseEntitiesBundle\Entity\Traits\Timestampable;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Species.
 */
class Species implements \JsonSerializable
{
    use BaseEntity,
        Nameable,
        Timestampable,
        Descriptible,
        Jsonable,
        OuterExtensible,
        SpeciesExtension
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
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $life_cycle;

    /**
     * @var int
     */
    private $legal_germination_rate = 0;

    /**
     * @var int
     */
    private $germination_rate = 0;

    /**
     * @var int
     */
    private $seed_lifespan;

    /**
     * @var int
     */
    private $raise_duration;

    /**
     * @var float
     */
    private $tkw;

    /**
     * @var string
     */
    private $regulatory_status;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $varieties;

    /**
     * @var \Librinfo\VarietiesBundle\Entity\Genus
     */
    private $genus;

    /**
     * @var \Librinfo\VarietiesBundle\Entity\Species
     */
    private $parent_species;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $subspecieses;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $plant_categories;

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
        $this->varieties = new ArrayCollection();
        $this->subspecieses = new ArrayCollection();
        $this->plant_categories = new ArrayCollection();
    }

    /**
     * Set latinName.
     *
     * @param string $latinName
     *
     * @return Species
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
     * @return Species
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
     * Set code.
     *
     * @param string $code
     *
     * @return Species
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
     * Set lifeCycle.
     *
     * @param string $lifeCycle
     *
     * @return Species
     */
    public function setLifeCycle($lifeCycle)
    {
        $this->life_cycle = $lifeCycle;

        return $this;
    }

    /**
     * Get lifeCycle.
     *
     * @return string
     */
    public function getLifeCycle()
    {
        return $this->life_cycle;
    }

    /**
     * Set legal_germination_rate.
     *
     * @param int $legalGerminationRate
     *
     * @return Species
     */
    public function setLegalGerminationRate($legalGerminationRate)
    {
        $this->legal_germination_rate = $legalGerminationRate;

        return $this;
    }

    /**
     * Get legal_germination_rate.
     *
     * @return int
     */
    public function getLegalGerminationRate()
    {
        return $this->legal_germination_rate;
    }

    /**
     * @return int
     */
    public function getGerminationRate(): int
    {
        return $this->germination_rate;
    }

    /**
     * @param int $germination_rate
     */
    public function setGerminationRate(?int $germination_rate): void
    {
        $this->germination_rate = $germination_rate;
    }

    /**
     * Set seed_lifespan.
     *
     * @param int $seedLifespan
     *
     * @return Species
     */
    public function setSeedLifespan($seedLifespan)
    {
        $this->seed_lifespan = $seedLifespan;

        return $this;
    }

    /**
     * Get seed_lifespan.
     *
     * @return int
     */
    public function getSeedLifespan()
    {
        return $this->seed_lifespan;
    }

    /**
     * Set raise_duration.
     *
     * @param int $raiseDuration
     *
     * @return Species
     */
    public function setRaiseDuration($raiseDuration)
    {
        $this->raise_duration = $raiseDuration;

        return $this;
    }

    /**
     * Get raise_duration.
     *
     * @return int
     */
    public function getRaiseDuration()
    {
        return $this->raise_duration;
    }

    /**
     * Set tkw.
     *
     * @param float $tkw
     *
     * @return Species
     */
    public function setTkw($tkw)
    {
        $this->tkw = $tkw;

        return $this;
    }

    /**
     * Get tkw.
     *
     * @return float
     */
    public function getTkw()
    {
        return $this->tkw;
    }

    /**
     * Set regulatoryStatus.
     *
     * @param string $regulatoryStatus
     *
     * @return Variety
     */
    public function setRegulatoryStatus($regulatoryStatus)
    {
        $this->regulatory_status = $regulatoryStatus;

        return $this;
    }

    /**
     * Get regulatoryStatus.
     *
     * @return string
     */
    public function getRegulatoryStatus()
    {
        return $this->regulatory_status;
    }

    /**
     * Set genus.
     *
     * @param \Librinfo\VarietiesBundle\Entity\Genus $genus
     *
     * @return Species
     */
    public function setGenus(\Librinfo\VarietiesBundle\Entity\Genus $genus = null)
    {
        $this->genus = $genus;

        return $this;
    }

    /**
     * Get genus.
     *
     * @return \Librinfo\VarietiesBundle\Entity\Genus
     */
    public function getGenus()
    {
        return $this->genus;
    }

    /**
     * Add variety.
     *
     * @param \Librinfo\VarietiesBundle\Entity\Variety $variety
     *
     * @return Species
     */
    public function addVariety(\Librinfo\VarietiesBundle\Entity\Variety $variety)
    {
        $variety->setSpecies($this);
        $this->varieties->add($variety);

        return $this;
    }

    /**
     * Remove variety.
     *
     * @param \Librinfo\VarietiesBundle\Entity\Variety $variety
     *
     * @return bool tRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeVariety(\Librinfo\VarietiesBundle\Entity\Variety $variety)
    {
        return $this->varieties->removeElement($variety);
    }

    /**
     * Get varieties.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVarieties()
    {
        return $this->varieties;
    }

    /**
     * Add subspecies.
     *
     * @param \Librinfo\VarietiesBundle\Entity\Species $subspecies
     *
     * @return Species
     */
    public function addSubspecies(\Librinfo\VarietiesBundle\Entity\Species $subspecies)
    {
        $subspecies->setParentSpecies($this);
        $this->subspecieses[] = $subspecies;

        return $this;
    }

    /**
     * Remove subspecies.
     *
     * @param \Librinfo\VarietiesBundle\Entity\Species $subspecies
     *
     * @return bool tRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeSubspeciese(\Librinfo\VarietiesBundle\Entity\Species $subspecies)
    {
        return $this->subspecieses->removeElement($subspecies);
    }

    /**
     * Get subspecieses.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubspecieses()
    {
        return $this->subspecieses;
    }

    /**
     * Has sub-specieses.
     *
     * @return bool
     */
    public function hasSubspecieses()
    {
        return count($this->subspecieses) > 0;
    }

    /**
     * Set parentSpecies.
     *
     * @param \Librinfo\VarietiesBundle\Entity\Species $parentSpecies
     *
     * @return Species
     */
    public function setParentSpecies(\Librinfo\VarietiesBundle\Entity\Species $parentSpecies = null)
    {
        $this->parent_species = $parentSpecies;

        return $this;
    }

    /**
     * Get parentSpecies.
     *
     * @return \Librinfo\VarietiesBundle\Entity\Species
     */
    public function getParentSpecies()
    {
        return $this->parent_species;
    }

    /**
     * Has parent species.
     *
     * @return bool
     */
    public function hasParentSpecies()
    {
        return $this->parent_species != null;
    }

    /**
     * Check if a species has a grand parent
     * This should never happen (used for validation methods).
     *
     * @return bool
     */
    public function hasGrandParentSpecies()
    {
        return $this->parent_species != null && $this->parent_species->getParentSpecies() != null;
    }

    /**
     * Add plantCategory.
     *
     * @param \Librinfo\VarietiesBundle\Entity\PlantCategory $plantCategory
     *
     * @return Variety
     */
    public function addPlantCategory(\Librinfo\VarietiesBundle\Entity\PlantCategory $plantCategory)
    {
        $this->plant_categories[] = $plantCategory;

        return $this;
    }

    /**
     * Remove plantCategory.
     *
     * @param \Librinfo\VarietiesBundle\Entity\PlantCategory $plantCategory
     *
     * @return bool tRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removePlantCategory(\Librinfo\VarietiesBundle\Entity\PlantCategory $plantCategory)
    {
        return $this->plant_categories->removeElement($plantCategory);
    }

    /**
     * Get plantCategories.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlantCategories()
    {
        return $this->plant_categories;
    }

    /**
     * Set plantCategories.
     */
    public function setPlantCategories($plant_categories)
    {
        $this->plant_categories = $plant_categories;
    }
}
