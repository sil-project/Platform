<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\TestsBundle\Entity;

use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\BaseEntity;

/**
 * TstParent.
 */
class TstParent
{
    use BaseEntity;

    /*
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $code;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $final;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $subparent;

    /**
     * @var \Blast\Bundle\TestsBundle\Entity\TstSecond
     */
    private $second;

    /**
     * @var \Blast\Bundle\TestsBundle\Entity\TstParent
     */
    private $parent_parent;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->final = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subparent = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return TstParent
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return TstParent
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
     * Add final.
     *
     * @param \Blast\Bundle\TestsBundle\Entity\TstFinal $final
     *
     * @return TstParent
     */
    public function addFinal(\Blast\Bundle\TestsBundle\Entity\TstFinal $final)
    {
        $this->final[] = $final;

        return $this;
    }

    /**
     * Remove final.
     *
     * @param \Blast\Bundle\TestsBundle\Entity\TstFinal $final
     */
    public function removeFinal(\Blast\Bundle\TestsBundle\Entity\TstFinal $final)
    {
        $this->final->removeElement($final);
    }

    /**
     * Get final.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFinal()
    {
        return $this->final;
    }

    /**
     * Add subparent.
     *
     * @param \Blast\Bundle\TestsBundle\Entity\TstParent $subparent
     *
     * @return TstParent
     */
    public function addSubparent(\Blast\Bundle\TestsBundle\Entity\TstParent $subparent)
    {
        $this->subparent[] = $subparent;

        return $this;
    }

    /**
     * Remove subparent.
     *
     * @param \Blast\Bundle\TestsBundle\Entity\TstParent $subparent
     */
    public function removeSubparent(\Blast\Bundle\TestsBundle\Entity\TstParent $subparent)
    {
        $this->subparent->removeElement($subparent);
    }

    /**
     * Get subparent.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubparent()
    {
        return $this->subparent;
    }

    /**
     * Set second.
     *
     * @param \Blast\Bundle\TestsBundle\Entity\TstSecond $second
     *
     * @return TstParent
     */
    public function setSecond(\Blast\Bundle\TestsBundle\Entity\TstSecond $second = null)
    {
        $this->second = $second;

        return $this;
    }

    /**
     * Get second.
     *
     * @return \Blast\Bundle\TestsBundle\Entity\TstSecond
     */
    public function getSecond()
    {
        return $this->second;
    }

    /**
     * Set parentParent.
     *
     * @param \Blast\Bundle\TestsBundle\Entity\TstParent $parentParent
     *
     * @return TstParent
     */
    public function setParentParent(\Blast\Bundle\TestsBundle\Entity\TstParent $parentParent = null)
    {
        $this->parent_parent = $parentParent;

        return $this;
    }

    /**
     * Get parentParent.
     *
     * @return \Blast\Bundle\TestsBundle\Entity\TstParent
     */
    public function getParentParent()
    {
        return $this->parent_parent;
    }
}
