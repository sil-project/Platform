<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Address\Tests\Unit\Fixtures;

use Sil\Component\Address\Model\Country;
use Sil\Component\Address\Model\CountryInterface;
use Sil\Component\Address\Model\City;
use Sil\Component\Address\Model\CityInterface;
use Sil\Component\Address\Model\PostCode;
use Sil\Component\Address\Model\PostCodeInterface;
use Sil\Component\Address\Model\Address;
use Sil\Component\Address\Model\AddressInterface;
use Blast\Component\Resource\Repository\InMemoryRepository;

class Fixtures
{
    /**
     * @var InMemoryRepository
     */
    private $countryRepository;

    /**
     * @var InMemoryRepository
     */
    private $cityRepository;

    /**
     * @var InMemoryRepository
     */
    private $postCodeRepository;

    /**
     * @var InMemoryRepository
     */
    private $addressRepository;

    private $rawData = [];

    public function __construct()
    {
        $this->rawData = [
            'Addresses' => [
                [
                    'street' => '7, Allée Jean Lagadic',
                    'other'  => null,
                    'city'   => '29232',
                ],
            ],
            'Cities' => [
                [
                    'name'     => 'Quimper',
                    'code'     => '29232',
                    'postCode' => '29000',
                    'country'  => 'FR',
                ],
                [
                    'name'     => 'Brest',
                    'code'     => '29019',
                    'postCode' => '29200',
                    'country'  => 'FR',
                ],
                [
                    'name'     => 'Morlaix',
                    'code'     => '29151',
                    'postCode' => '29600',
                    'country'  => 'FR',
                ],
                [
                    'code'     => '29046',
                    'name'     => 'Douarnenez',
                    'postCode' => '29100',
                    'country'  => 'FR',
                ],
                [
                    'code'     => '29224',
                    'name'     => 'Pouldergat',
                    'postCode' => '29100',
                    'country'  => 'FR',
                ],
                [
                    'code'     => '29090',
                    'name'     => 'Kerlaz',
                    'postCode' => '29100',
                    'country'  => 'FR',
                ],
                [
                    'code'     => '29226',
                    'name'     => 'Poullan sur mer',
                    'postCode' => '29100',
                    'country'  => 'FR',
                ],
                [
                    'code'     => '29087',
                    'name'     => 'Le juch',
                    'postCode' => '29100',
                    'country'  => 'FR',
                ],
            ],
            'Countries' => [
                [
                    'name' => 'France',
                    'code' => 'FR',
                ],
                [
                    'name' => 'Irland',
                    'code' => 'IE',
                ],
            ],
            'Postcodes' => [
                [
                    'code' => '29000',
                ],
                [
                    'code' => '29100',
                ],
                [
                    'code' => '29200',
                ],
                [
                    'code' => '29600',
                ],
            ],
        ];

        $this->countryRepository = new InMemoryRepository(CountryInterface::class);
        $this->cityRepository = new InMemoryRepository(CityInterface::class);
        $this->postCodeRepository = new InMemoryRepository(PostCodeInterface::class);
        $this->addressRepository = new InMemoryRepository(AddressInterface::class);

        $this->generateFixtures();
    }

    private function generateFixtures(): void
    {
        $this->loadCountries();
        $this->loadPostCodes();
        $this->loadCities();
        $this->loadAddresses();
    }

    private function loadCountries(): void
    {
        foreach ($this->rawData['Countries'] as $countryData) {
            $country = new Country($countryData['name'], $countryData['code']);
            $this->getCountryRepository()->add($country);
        }
    }

    private function loadPostCodes(): void
    {
        foreach ($this->rawData['Postcodes'] as $postCodeData) {
            $postcode = new PostCode($postCodeData['code']);
            $this->getPostCodeRepository()->add($postcode);
        }
    }

    private function loadCities(): void
    {
        foreach ($this->rawData['Cities'] as $cityData) {
            $postCode = $this->getPostCodeRepository()->findOneBy(['code' => $cityData['postCode']]);
            $country = $this->getCountryRepository()->findOneBy(['code' => $cityData['country']]);
            $city = new City($cityData['name'], $postCode, $country);
            $city->setCode($cityData['code']);
            $this->getCityRepository()->add($city);
        }
    }

    private function loadAddresses(): void
    {
        foreach ($this->rawData['Addresses'] as $addressData) {
            $city = $this->getCityRepository()->findOneBy(['code' => $addressData['city']]);
            $address = new Address($addressData['street'], $city);
            $this->getAddressRepository()->add($address);
        }
    }

    /**
     * @return InMemoryRepository
     */
    public function getCountryRepository(): InMemoryRepository
    {
        return $this->countryRepository;
    }

    /**
     * @return InMemoryRepository
     */
    public function getCityRepository(): InMemoryRepository
    {
        return $this->cityRepository;
    }

    /**
     * @return InMemoryRepository
     */
    public function getPostCodeRepository(): InMemoryRepository
    {
        return $this->postCodeRepository;
    }

    /**
     * @return InMemoryRepository
     */
    public function getAddressRepository(): InMemoryRepository
    {
        return $this->addressRepository;
    }

    /**
     * @return array
     */
    public function getRawData(): array
    {
        return $this->rawData;
    }
}
