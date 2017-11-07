<?php

declare(strict_types=1);

/*
 * This file is part of the Blast Project package.
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
use Blast\BaseEntitiesBundle\Entity\Traits\Guidable;
use InvalidArgumentException;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class Warehouse
{
    use Guidable;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $code;

    /**
     * @var Collection|Location[]
     */
    private $locations;

    public static function createDefault(string $code, string $name)
    {
        $o = new self();
        $o->code = $code;
        $o->name = $name;

        return $o;
    }

    public function __construct()
    {
        $this->locations = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return Collection
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param type $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @param Location $location
     *
     * @return bool
     */
    public function hasLocation(Location $location): bool
    {
        return $this->locations->contains($location);
    }

    /**
     * @param Location $location
     *
     * @throws InvalidArgumentException
     */
    public function addLocation(Location $location): void
    {
        if ($this->locations->contains($location)) {
            throw new \InvalidArgumentException(
                    'The same Location cannot be added twice');
        }
        $location->setWarehouse($this);
        $this->locations->add($location);
    }

    /**
     * @param Location $location
     *
     * @throws InvalidArgumentException
     */
    public function removeLocation(Location $location): void
    {
        if (!$this->locations->contains($location)) {
            throw new \InvalidArgumentException(
                    'The location to remove does not exist');
        }
        $location->setWarehouse(null);
        $this->locations->removeElement($location);
    }
}
