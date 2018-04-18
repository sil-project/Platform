<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use Blast\Bundle\TestsBundle\Codeception\Step\Acceptance\Product as ProductTester;
use Blast\Bundle\TestsBundle\Codeception\Step\Acceptance\ProductOption as OptionTester;
use Blast\Bundle\TestsBundle\Codeception\Step\Acceptance\ProductAttribute as AttributeTester;

/**
 * @group product
 * @group all
 */
class ProductCest
{
    public function testProducts(ProductTester $i)
    {
        $i->loginSil('sil@sil.eu', 'sil');
        $i->homePage();
        $i->createProduct();
        $i->listProducts();
    }

    public function testOptions(OptionTester $i)
    {
        $optionTypeName = 'sil-product-option-type' . $i->getRandNbr();

        $i->loginSil('sil@sil.eu', 'sil');
        $i->homePage();
        $i->listOptionTypes();
        $i->createOptionType($optionTypeName);
        $i->createOption($optionTypeName, 'test-value');
        $i->deleteOptionType($optionTypeName);
    }

    public function testAttributes(AttributeTester $i)
    {
        $attributeTypeName = 'sil-product-attribute-type' . $i->getRandNbr();

        $i->loginSil('sil@sil.eu', 'sil');
        $i->homePage();
        $i->listAttributeTypes();
        $i->createAttributeType($attributeTypeName);
        $i->createAttribute($attributeTypeName, 'test-value');
        $i->deleteAttributeType($attributeTypeName);
    }
}
