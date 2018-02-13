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

    public function testValueOfReusableAttributeCannotBeChanged()
    {
        $product = $this->fixtures->getProductRepository()->findOneBy(['code.value' => 'SHOE001']);

        $brands = $this->fixtures->getAttributeTypeRepository()->findOneBy(['name' => 'Brand']);

        $attribute = $product->getAttribute($brands);

        $this->assertTrue($attribute->isReusable());

        $this->expectException(\InvalidArgumentException::class);

        $attribute->setValue('a new value');
    }

    public function testValueOfNonReusableAttributeCanBeChanged()
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
}
