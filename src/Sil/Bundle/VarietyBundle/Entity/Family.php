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
 * Family.
 */
class Family implements FamilyInterface
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
     * @var Collection
     */
    protected $genuses;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->genuses = new ArrayCollection();
    }

    public function __clone()
    {
        $this->id = null;
        $this->initCollections();
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
     * @param Genus $genus
     *
     * @return Family
     */
    public function addGenus(Genus $genus)
    {
        $genus->setFamily($this);
        $this->genuses->add($genus);

        return $this;
    }

    /**
     * Remove genus.
     *
     * @param Genus $genus
     *
     * @return bool tRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeGenus(Genus $genus)
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
