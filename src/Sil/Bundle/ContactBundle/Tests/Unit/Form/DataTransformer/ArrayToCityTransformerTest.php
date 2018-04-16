<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Tests\Unit\Form\DataTransformer;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Sil\Bundle\ContactBundle\Form\DataTransformer\ArrayToCityTransformer;
use Sil\Bundle\ContactBundle\Repository\CountryRepository;
use Sil\Bundle\ContactBundle\Repository\ProvinceRepository;
use Sil\Bundle\ContactBundle\Entity\City;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 *
 * @coversDefaultClass \Sil\Bundle\ContactBundle\Form\DataTransformer\ArrayToCityTransformer
 */
class ArrayToCityTransformerTest extends TestCase
{
    public function test_reverse_transform()
    {
        $city = [
            'name'      => 'foo',
            'postCode'  => '00000',
            'code'      => 'FOO',
        ];

        $transformer = $this->getTransformer();

        $transformed = $transformer->reverseTransform($city);

        $this->assertInstanceOf(City::class, $transformed);
        $this->assertSame($city['name'], $transformed->getName());
        $this->assertSame($city['postCode'], $transformed->getPostCode());
        $this->assertSame($city['code'], $transformed->getCode());
    }

    public function test_reverse_transform_without_name()
    {
        $city = [
            'postCode'  => '00000',
            'code'      => 'FOO',
        ];

        $transformer = $this->getTransformer();

        $this->expectException(TransformationFailedException::class);

        $transformer->reverseTransform($city);
    }

    public function test_reverse_transform_without_postcode()
    {
        $city = [
            'name'      => 'foo',
            'code'      => 'FOO',
        ];

        $transformer = $this->getTransformer();

        $this->expectException(TransformationFailedException::class);

        $transformer->reverseTransform($city);
    }

    public function test_transform()
    {
        $city = new City('foo', '00000');

        $transformer = $this->getTransformer();

        $transformed = $transformer->transform($city);

        $this->assertInternalType('array', $transformed);
        $this->assertSame($transformed['name'], $city->getName());
        $this->assertSame($transformed['postCode'], $city->getPostCode());
    }

    /**
     *  Get ArrayToCityTransformer instance.
     *
     * @return ArrayToCityTransformer
     */
    private function getTransformer()
    {
        $countryRepository = $this->createMock(CountryRepository::class);
        $provinceRepository = $this->createMock(ProvinceRepository::class);

        return new ArrayToCityTransformer($countryRepository, $provinceRepository);
    }
}
