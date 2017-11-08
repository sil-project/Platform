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

namespace Blast\Bundle\BaseEntitiesBundle\Entity\Traits;

use Doctrine\Common\Collections\ArrayCollection;

trait NestedTreeable
{
    /**
     * @var int
     */
    protected $treeLft;

    /**
     * @var int
     */
    protected $treeRgt;

    /**
     * @var int
     */
    protected $treeLvl;

    /**
     * @var \Entity\Category
     */
    protected $treeRoot;

    /**
     * @var \Entity\Category
     */
    protected $treeParent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $treeChildren;

    /**
     * Set treeLft.
     *
     * @param int $treeLft
     *
     * @return mixed $this
     */
    public function setTreeLft($treeLft)
    {
        $this->treeLft = $treeLft;

        return $this;
    }

    /**
     * Get treeLft.
     *
     * @return int
     */
    public function getTreeLft()
    {
        return $this->treeLft;
    }

    /**
     * Set treeRgt.
     *
     * @param int $treeRgt
     *
     * @return mixed $this
     */
    public function setTreeRgt($treeRgt)
    {
        $this->treeRgt = $treeRgt;

        return $this;
    }

    /**
     * Get treeRgt.
     *
     * @return int
     */
    public function getTreeRgt()
    {
        return $this->treeRgt;
    }

    /**
     * Set treeLvl.
     *
     * @param int $treeLvl
     *
     * @return mixed $this
     */
    public function setTreeLvl($treeLvl)
    {
        $this->treeLvl = $treeLvl;

        return $this;
    }

    /**
     * Get treeLvl.
     *
     * @return int
     */
    public function getTreeLvl()
    {
        return $this->treeLvl;
    }

    /**
     * Add treeTreeChildren.
     *
     * @param object $treeChild
     *
     * @return mixed $this
     */
    public function addTreeChild($treeChild)
    {
        $this->treeChildren[] = $treeChild;

        return $this;
    }

    /**
     * Remove treeChild.
     *
     * @param object $treeChild
     */
    public function removeTreeChild($treeChild)
    {
        $this->treeChildren->removeElement($treeChild);
    }

    /**
     * Get treeChildren.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTreeChildren()
    {
        return $this->treeChildren;
    }

    /**
     * Set treeRoot.
     *
     * @param object $treeRoot
     *
     * @return mixed $this
     */
    public function setTreeRoot($treeRoot = null)
    {
        $this->treeRoot = $treeRoot;

        return $this;
    }

    /**
     * Get treeRoot.
     *
     * @return object
     */
    public function getTreeRoot()
    {
        return $this->treeRoot;
    }

    /**
     * Set treeParent.
     *
     * @param object $treeParent
     *
     * @return mixed
     */
    public function setTreeParent($treeParent = null)
    {
        $this->treeParent = $treeParent;

        return $this;
    }

    /**
     * Get treeParent.
     *
     * @return object
     */
    public function getTreeParent()
    {
        return $this->treeParent;
    }

    public function initCollections()
    {
        $this->treeChildren = new ArrayCollection();
    }
}
