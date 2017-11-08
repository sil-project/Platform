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

use Doctrine\Common\Collections\ArrayCollection;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Tree\NodeInterface;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Nameable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\NestedTreeable;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Jsonable;

/**
 * PlantCategory.
 */
class PlantCategory implements \JsonSerializable
{
    use BaseEntity,
        Nameable,
        NestedTreeable,
        Jsonable
    ;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $species;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $varieties;

    /**
     * Constructor.
     */
    public function __construct()
    {
        //init NesteTreeable trreeChildren Collection
        $this->initCollections();
        $this->species = new ArrayCollection();
        $this->varieties = new ArrayCollection();
    }

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return PlantCategory
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
     * Add variety.
     *
     * @param \Sil\Bundle\VarietyBundle\Entity\Variety $variety
     *
     * @return PlantCategory
     */
    public function addVariety(\Sil\Bundle\VarietyBundle\Entity\Variety $variety)
    {
        $this->varieties[] = $variety;

        return $this;
    }

    /**
     * Remove variety.
     *
     * @param \Sil\Bundle\VarietyBundle\Entity\Variety $variety
     *
     * @return bool tRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeVariety(\Sil\Bundle\VarietyBundle\Entity\Variety $variety)
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
     * Add species.
     *
     * @param \Sil\Bundle\VarietyBundle\Entity\Species $species
     *
     * @return PlantCategory
     */
    public function addSpecies(\Sil\Bundle\VarietyBundle\Entity\Species $species)
    {
        $this->species[] = $species;

        return $this;
    }

    /**
     * Remove species.
     *
     * @param \Sil\Bundle\VarietyBundle\Entity\Species $species
     *
     * @return bool tRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeSpecies(\Sil\Bundle\VarietyBundle\Entity\Species $species)
    {
        return $this->species->removeElement($species);
    }

    /**
     * Get species.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpecies()
    {
        return $this->species;
    }

    public function setChildNodeOf(NodeInterface $node)
    {
        $path = rtrim($node->getRealMaterializedPath(), static::getMaterializedPathSeparator());
        $this->setMaterializedPath($path);
        $this->setSortMaterializedPath($path . static::getMaterializedPathSeparator() . $this->getId());

        if (null !== $this->parentNode) {
            $this->parentNode->getChildNodes()->removeElement($this);
        }

        $this->parentNode = $node;
        $this->parentNode->addChildNode($this);

        foreach ($this->getChildNodes() as $child) {
            $child->setChildNodeOf($this);
        }

        return $this;
    }
}
