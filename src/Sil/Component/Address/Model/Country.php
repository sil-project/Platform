<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Address\Model;

use InvalidArgumentException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Country implements CountryInterface
{
    /**
     * The name of the country.
     *
     * @var string
     */
    protected $name;

    /**
     * The code of the country.
     *
     * @var string
     */
    protected $code;

    /**
     * Collection of cities.
     *
     * @var Collection|CityInterface[]
     */
    protected $cities;

    public function __construct(string $name, string $code)
    {
        $this->name = $name;
        $this->code = $code;

        $this->cities = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    public function getCities(): array
    {
        return $this->cities->getValues();
    }

    /**
     * {@inheritdoc}
     */
    public function addCity(CityInterface $city): void
    {
        if ($this->cities->contains($city)) {
            throw new InvalidArgumentException(sprintf('City « %s » already belongs to the country « %s »', $city->getName(), $this->getName()));
        }
        $this->cities->add($city);
    }

    /**
     * {@inheritdoc}
     */
    public function removeCity(CityInterface $city): void
    {
        if (!$this->cities->contains($city)) {
            throw new InvalidArgumentException(sprintf('City « %s » does not belongs to the country « %s »', $city->getName(), $this->getName()));
        }
        $this->cities->removeElement($city);
    }
}
