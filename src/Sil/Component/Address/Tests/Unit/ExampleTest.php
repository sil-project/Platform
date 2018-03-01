<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Address\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sil\Component\Address\Tests\Unit\Fixtures\Fixtures;
use Sil\Component\Address\Model\Address;
use Sil\Component\Address\Model\City;
use Sil\Component\Address\Model\Country;
use Sil\Component\Address\Model\PostCode;

class ExampleTest extends TestCase
{
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = new Fixtures();
    }

    public function test_example_of_building_address()
    {
        $street = '12, Rue de la crèperie';
        $city = $this->fixtures->getCityRepository()->findOneBy(['name' => 'Morlaix']);
        $morlaixPostCode = $this->fixtures->getPostCodeRepository()->findOneBy(['code' => '29600']);
        $france = $this->fixtures->getCountryRepository()->findOneBy(['code' => 'FR']);

        $address = new Address($street, $city);

        $this->assertEquals($street, $address->getStreet());
        $this->assertEquals($city, $address->getCity());
        $this->assertEquals($france, $address->getCity()->getCountry());
        $this->assertEquals($morlaixPostCode, $address->getCity()->getPostCode());
    }

    public function test_example_creating_city_with_country()
    {
        $newPostCode = new PostCode('AF0001');
        $newCountry = new Country('Ideal Country', 'IC');
        $newCity = new City('A fake city', $newPostCode, $newCountry);

        $this->assertEquals($newCountry, $newCity->getCountry());
    }
}
