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

use Doctrine\Common\Collections\ArrayCollection;
use Blast\BaseEntitiesBundle\Entity\Traits\Tree\NodeInterface;
use Blast\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\BaseEntitiesBundle\Entity\Traits\Nameable;
use Blast\BaseEntitiesBundle\Entity\Traits\NestedTreeable;
use Blast\BaseEntitiesBundle\Entity\Traits\Jsonable;

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
    private $code;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $species;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $varieties;

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
     * @param \Librinfo\VarietiesBundle\Entity\Variety $variety
     *
     * @return PlantCategory
     */
    public function addVariety(\Librinfo\VarietiesBundle\Entity\Variety $variety)
    {
        $this->varieties[] = $variety;

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
     * Add species.
     *
     * @param \Librinfo\VarietiesBundle\Entity\Species $species
     *
     * @return PlantCategory
     */
    public function addSpecies(\Librinfo\VarietiesBundle\Entity\Species $species)
    {
        $this->species[] = $species;

        return $this;
    }

    /**
     * Remove species.
     *
     * @param \Librinfo\VarietiesBundle\Entity\Species $species
     *
     * @return bool tRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeSpecies(\Librinfo\VarietiesBundle\Entity\Species $species)
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
