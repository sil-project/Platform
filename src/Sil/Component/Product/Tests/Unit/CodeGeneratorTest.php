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
use Sil\Component\Product\Generator\ProductCodeGenerator;
use Sil\Component\Product\Generator\ProductVariantCodeGenerator;

class CodeGeneratorTest extends TestCase
{
    /**
     * @var Fixtures
     */
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = new Fixtures();
    }

    public function testProductCodeGenerator()
    {
        $codeGenerator = new ProductCodeGenerator();
        $code = $codeGenerator->generate('Product Test');

        $this->assertTrue($codeGenerator->isValid($code));
        $this->assertEquals('PRODUCT', $code->getValue());
    }

    public function testProductVariantCodeGenerator()
    {
        $product = $this->fixtures->getProductRepository()->findOneBy(['code.value' => 'TSHT001']);

        $codeGenerator = new ProductVariantCodeGenerator();

        $productOptions = [
            $this->fixtures->getOptionRepository()->findOneBy(['optionType.name' => 'Color', 'value' => 'Red']),
            $this->fixtures->getOptionRepository()->findOneBy(['optionType.name' => 'Shoe Size', 'value' => '42']),
        ];

        $code = $codeGenerator->generate($product->getCode(), $productOptions);

        $this->assertTrue($codeGenerator->isValid($code));
        $this->assertEquals('TSHT001-RED-42', $code->getValue());

        $productOptions = [
            $this->fixtures->getOptionRepository()->findOneBy(['optionType.name' => 'Shoe Size', 'value' => '42']),
            $this->fixtures->getOptionRepository()->findOneBy(['optionType.name' => 'Color', 'value' => 'Red']),
        ];

        $code = $codeGenerator->generate($product->getCode(), $productOptions);

        $this->assertTrue($codeGenerator->isValid($code));
        $this->assertEquals('TSHT001-42-RED', $code->getValue());
    }
}
