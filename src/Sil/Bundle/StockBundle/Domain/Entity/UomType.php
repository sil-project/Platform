<?php

declare(strict_types=1);

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Domain\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class UomType
{
    use Guidable;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Collection|Uom[]
     */
    protected $uoms;

    /**
     * @param string $name
     */
    public static function createDefault(string $name)
    {
        $o = new self();
        $o->name = $name;

        return $o;
    }

    public function __construct()
    {
        $this->uoms = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function __toString(): string
    {
        return (string) $this->getName();
    }

    public function getUoms(): Collection
    {
        return $this->uoms;
    }
}
