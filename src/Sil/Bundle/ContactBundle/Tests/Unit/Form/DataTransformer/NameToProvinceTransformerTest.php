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
use Sil\Component\Contact\Model\ProvinceInterface;
use Sil\Bundle\ContactBundle\Form\DataTransformer\NameToProvinceTransformer;
use Sil\Bundle\ContactBundle\Tests\Resources\Repository\ProvinceRepository;
use Sil\Bundle\ContactBundle\Entity\Province;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 *
 * @coversDefaultClass \Sil\Bundle\ContacteBundle\Form\DataTransformer\NameToProvinceTransformer
 */
class NameToProvinceTransformerTest extends TestCase
{
    public function test_transform()
    {
        $name = 'foo';

        $province = new Province($name);

        $repository = new ProvinceRepository(ProvinceInterface::class);

        $repository->add($province);

        $transformer = new NameToProvinceTransformer($repository);

        $transformed = $transformer->transform($name);

        $this->assertInstanceOf(Province::class, $transformed);
        $this->assertSame($transformed->getName(), $name);
        $this->assertSame($transformed, $province);
    }

    public function test_reverse_transform()
    {
        $province = new Province('foo');

        $repository = new ProvinceRepository(ProvinceInterface::class);

        $repository->add($province);

        $transformer = new NameToProvinceTransformer($repository);

        $transformed = $transformer->reverseTransform($province);

        $this->assertInternalType('string', $transformed);
        $this->assertSame($transformed, $province->getName());
    }
}
