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
use Sil\Component\Product\Service\ProductService;

class ProductServiceTest extends TestCase
{
    /**
     * @var Fixtures
     */
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = new Fixtures();
    }

    public function testGenerateVariantsService()
    {
        $product = $this->fixtures->getProductRepository()->findOneBy(['code.value' => 'SHOE001']);

        $productService = new ProductService();
        $productService->generateVariantsForProduct($product);

        $variants = $product->getVariants();
        $expectedVariantTotalCount = count($this->fixtures->getRawData()['Shoe Size']) * count($this->fixtures->getRawData()['Color']);

        $this->assertCount($expectedVariantTotalCount, $variants); // Check if all variants all generate

        $variantCodes = [];

        array_walk($variants, function ($variant) use (&$variantCodes) {
            $variantCodes[] = $variant->getCode()->getValue();
        });

        $this->assertContains('SHOE001-BLUE-42', $variantCodes);
    }
}
