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

class City implements CityInterface
{
    /**
     * The name of the city.
     *
     * @var string
     */
    protected $name;

    /**
     * The code of the city.
     *
     * @var string
     */
    protected $code;

    /**
     * The city's postcode.
     *
     * @var PostCodeInterface
     */
    protected $postCode;

    /**
     * The city's country.
     *
     * @var CountryInterface
     */
    protected $country;

    public function __construct(string $name, PostCodeInterface $postCode, CountryInterface $country)
    {
        $this->name = $name;
        $this->postCode = $postCode;
        $this->country = $country;

        $postCode->addCity($this);
        $country->addCity($this);
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
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * {@inheritdoc}
     */
    public function getPostCode(): PostCodeInterface
    {
        return $this->postCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountry(): CountryInterface
    {
        return $this->country;
    }
}
