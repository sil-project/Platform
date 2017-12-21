<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\TestsBundle\Entity;

/**
 * TstFinal.
 */
class TstFinal
{
    /**
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
    private $final_children;

    /**
     * @var \Blast\Bundle\TestsBundle\Entity\TstFinal
     */
    private $final_parent;

    /**
     * @var \Blast\Bundle\TestsBundle\Entity\TstParent
     */
    private $parent;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->final_children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return TstFinal
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
     * @return TstFinal
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
     * Add finalChild.
     *
     * @param \Blast\Bundle\TestsBundle\Entity\TstFinal $finalChild
     *
     * @return TstFinal
     */
    public function addFinalChild(\Blast\Bundle\TestsBundle\Entity\TstFinal $finalChild)
    {
        $this->final_children[] = $finalChild;

        return $this;
    }

    /**
     * Remove finalChild.
     *
     * @param \Blast\Bundle\TestsBundle\Entity\TstFinal $finalChild
     */
    public function removeFinalChild(\Blast\Bundle\TestsBundle\Entity\TstFinal $finalChild)
    {
        $this->final_children->removeElement($finalChild);
    }

    /**
     * Get finalChildren.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFinalChildren()
    {
        return $this->final_children;
    }

    /**
     * Set finalParent.
     *
     * @param \Blast\Bundle\TestsBundle\Entity\TstFinal $finalParent
     *
     * @return TstFinal
     */
    public function setFinalParent(\Blast\Bundle\TestsBundle\Entity\TstFinal $finalParent = null)
    {
        $this->final_parent = $finalParent;

        return $this;
    }

    /**
     * Get finalParent.
     *
     * @return \Blast\Bundle\TestsBundle\Entity\TstFinal
     */
    public function getFinalParent()
    {
        return $this->final_parent;
    }

    /**
     * Set parent.
     *
     * @param \Blast\Bundle\TestsBundle\Entity\TstParent $parent
     *
     * @return TstFinal
     */
    public function setParent(\Blast\Bundle\TestsBundle\Entity\TstParent $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return \Blast\Bundle\TestsBundle\Entity\TstParent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @var int
     */
    private $id;

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return TstFinal
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
