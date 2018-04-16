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
use Sil\Component\Contact\Model\CountryInterface;
use Sil\Bundle\ContactBundle\Form\DataTransformer\NameToCountryTransformer;
use Sil\Bundle\ContactBundle\Tests\Resources\Repository\CountryRepository;
use Sil\Bundle\ContactBundle\Entity\Country;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 *
 * @coversDefaultClass \Sil\Bundle\ContactBundle\Form\DataTransformer\NameToCountryTransformer
 */
class NameToCountryTransformerTest extends TestCase
{
    public function test_transform()
    {
        $name = 'foo';

        $country = new Country($name);

        $repository = new CountryRepository(CountryInterface::class);

        $repository->add($country);

        $transformer = new NameToCountryTransformer($repository);

        $transformed = $transformer->transform($name);

        $this->assertInstanceOf(Country::class, $transformed);
        $this->assertSame($transformed->getName(), $name);
        $this->assertSame($transformed, $country);
    }

    public function test_reverse_transform()
    {
        $country = new Country('foo');

        $repository = new CountryRepository(CountryInterface::class);

        $repository->add($country);

        $transformer = new NameToCountryTransformer($repository);

        $transformed = $transformer->reverseTransform($country);

        $this->assertInternalType('string', $transformed);
        $this->assertSame($transformed, $country->getName());
    }
}
