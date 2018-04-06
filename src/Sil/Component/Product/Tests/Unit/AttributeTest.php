<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Product\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sil\Component\Product\Tests\Unit\Fixtures\Fixtures;

class AttributeTest extends TestCase
{
    /**
     * @var Fixtures
     */
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = new Fixtures();
    }

    public function test_value_of_reusable_attribute_cannot_be_changed()
    {
        $this->markTestSkipped(
            'The rule that avoid change of reusable attribute value has been changed.'
        );

        $product = $this->fixtures->getProductRepository()->findOneBy(['code.value' => 'SHOE001']);

        $brands = $this->fixtures->getAttributeTypeRepository()->findOneBy(['name' => 'Brand']);

        $attribute = $product->getAttribute($brands);

        $this->assertTrue($attribute->isReusable());

        $this->expectException(\InvalidArgumentException::class);

        $attribute->setValue('a new value');
    }

    public function test_value_of_non_reusable_attribute_can_be_changed()
    {
        $product = $this->fixtures->getProductRepository()->findOneBy(['code.value' => 'SHOE001']);
        $brands = $this->fixtures->getAttributeTypeRepository()->findOneBy(['name' => 'Shoelace length']);

        $attribute = $product->getAttribute($brands);

        $this->assertFalse($attribute->isReusable());

        $attribute->setValue('a new value');

        $product = $this->fixtures->getProductRepository()->findOneBy(['code.value' => 'SHOE001']);
        $brands = $this->fixtures->getAttributeTypeRepository()->findOneBy(['name' => 'Shoelace length']);

        $attribute = $product->getAttribute($brands);

        $this->assertEquals('a new value', $attribute->getValue());
    }

    public function test_adding_attribute_of_attribute_type_twice_throws_exception()
    {
        $product = $this->fixtures->getProductRepository()->findOneBy(['code.value' => 'SHOE001']);

        $brandAttributeType = $this->fixtures->getAttributeTypeRepository()->findOneBy(['name' => 'Brand']);
        $brands = $this->fixtures->getAttributeRepository()->findBy(['attributeType' => $brandAttributeType]);

        $this->expectException(\InvalidArgumentException::class);

        $product->addAttribute($brands[1]);
    }
}
