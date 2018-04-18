<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\TestsBundle\Codeception\Step\Acceptance;

class Product extends Sil
{
    public function createProduct($options = null)
    {
        $productName = 'sil-product' . $this->getRandNbr();

        $this->amOnPage($this->getSilUrl() . '/product/create');
        $this->stdCheck();
        $this->waitForText('Nom', 30);
        $this->fillField('//input[@id="product_create_name"]', $productName);
        $this->click('//button[@type="submit"]');
        $this->waitForText('Produit', 30);
        $this->waitForText($productName, 30);
        $this->stdCheck();
        $this->cantSee('Créez un nouveau produit');
    }

    public function listProducts()
    {
        $this->amOnPage($this->getSilUrl() . '/product/list');
        $this->stdCheck();
        $this->waitForText('Liste des produits', 30);
    }

    public function homePage()
    {
        $this->amOnPage($this->getSilUrl() . '/product');
        $this->stdCheck();
        $this->waitForText('Liste des produits', 30);
    }
}
