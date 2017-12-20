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
 * TstSimple.
 */
class TstSimple
{
    use BaseEntity;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $second;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->second = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return TstSimple
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
     * Add second.
     *
     * @param \Blast\Bundle\TestsBundle\Entity\TstSecond $second
     *
     * @return TstSimple
     */
    public function addSecond(\Blast\Bundle\TestsBundle\Entity\TstSecond $second)
    {
        $this->second[] = $second;

        return $this;
    }

    /**
     * Remove second.
     *
     * @param \Blast\Bundle\TestsBundle\Entity\TstSecond $second
     */
    public function removeSecond(\Blast\Bundle\TestsBundle\Entity\TstSecond $second)
    {
        $this->second->removeElement($second);
    }

    /**
     * Get second.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSecond()
    {
        return $this->second;
    }

    /**
     * @var string
     */
    private $code;

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return TstSimple
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
}
