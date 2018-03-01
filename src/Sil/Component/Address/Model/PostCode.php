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

class PostCode implements PostCodeInterface
{
    /**
     * Post code.
     *
     * @var string
     */
    protected $code;

    /**
     * The cities associated to this postcode.
     *
     * @var Collection|CityInterface[]
     */
    protected $cities;

    public function __construct(string $code)
    {
        $this->code = $code;
        $this->cities = new ArrayCollection();
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
            throw new InvalidArgumentException(sprintf('City « %s » is already associated to the postcode « %s »', $city->getName(), $this->getCode()));
        }
        $this->cities->add($city);
    }

    /**
     * {@inheritdoc}
     */
    public function removeCity(CityInterface $city): void
    {
        if (!$this->cities->contains($city)) {
            throw new InvalidArgumentException(sprintf('City « %s » is not associated to postcode « %s »', $city->getName(), $this->getCode()));
        }
        $this->cities->removeElement($city);
    }
}
