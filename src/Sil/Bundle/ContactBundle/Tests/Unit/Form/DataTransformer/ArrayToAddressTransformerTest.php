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
use Sil\Bundle\ContactBundle\Form\DataTransformer\ArrayToAddressTransformer;
use Sil\Bundle\ContactBundle\Repository\ContactRepository;
use Sil\Bundle\ContactBundle\Entity\City;
use Sil\Bundle\ContactBundle\Entity\Address;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 *
 * @coversDefaultClass \Sil\Bundle\ContactBundle\Form\DataTransformer\ArrayToAddressTransformer
 */
class ArrayToAddressTransformerTest extends TestCase
{
    public function test_reverse_transform()
    {
        $address = [
            'city'     => $this->createMock(City::class),
            'province' => 'bar',
            'country'  => 'bazland',
            'street'   => 'foo',
        ];

        $transformer = $this->getTransformer();

        $transformed = $transformer->reverseTransform($address);

        $this->assertInstanceOf(Address::class, $transformed);
        $this->assertSame($transformed->getProvince(), $address['province']);
        $this->assertSame($transformed->getCountry(), $address['country']);
        $this->assertSame($transformed->getStreet(), $address['street']);
    }

    public function test_reverse_transform_without_street()
    {
        $address = [
            'city'     => $this->createMock(City::class),
            'province' => 'bar',
            'country'  => 'bazland',
        ];

        $transformer = $this->getTransformer();

        $this->expectException(TransformationFailedException::class);

        $transformer->reverseTransform($address);
    }

    public function test_reverse_transform_without_city()
    {
        $address = [
            'street'   => 'foo avenue',
            'province' => 'bar',
            'country'  => 'bazland',
        ];

        $transformer = $this->getTransformer();

        $this->expectException(TransformationFailedException::class);

        $transformer->reverseTransform($address);
    }

    public function test_reverse_transform_without_country()
    {
        $address = [
            'city'     => $this->createMock(City::class),
            'street'   => 'foo avenue',
            'province' => 'bar',
        ];

        $transformer = $this->getTransformer();

        $this->expectException(TransformationFailedException::class);

        $transformer->reverseTransform($address);
    }

    /**
     *  Get ArrayToAddressTransformer instance.
     *
     * @return ArrayToAddressTransformer
     */
    private function getTransformer()
    {
        $contactRepository = $this->createMock(ContactRepository::class);

        return new ArrayToAddressTransformer($contactRepository);
    }
}
