<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\TestsBundle\Codeception\Step\Acceptance;

class ProductAttribute extends Sil
{
    public function homePage()
    {
        $this->amOnPage($this->getSilUrl() . '/product/attribute_type');
        $this->stdCheck();
        $this->waitForText('Types d\'attributs', 30);
    }

    public function createAttributeType($name = null)
    {
        if ($name === null) {
            $name = 'sil-product-attribute-type' . $this->getRandNbr();
        }

        $this->amOnPage($this->getSilUrl() . '/product/attribute_type/create');
        $this->stdCheck();
        $this->waitForText('Nouveau type d\'attribut', 30);
        $this->waitForText('Nom', 30);
        $this->fillField('//input[@id="product_attribute_type_create_name"]', $name);
        $this->click('//label[@for="product_attribute_type_create_reusable"]');
        $this->click('//button[@type="submit"]');
        $this->waitForText('Types d\'attributs', 30);
        $this->waitForText($name, 30);
        $this->stdCheck();
        $this->cantSee('Créez un nouveau type d\'attribut');
    }

    public function createAttribute($attributeType, $value = null)
    {
        if ($value === null) {
            $value = 'sil-product-attribute-value' . $this->getRandNbr();
        }

        $this->amOnPage($this->getSilUrl() . '/product/attribute/create');
        $this->stdCheck();
        $this->waitForText('Nouvel attribut', 30);
        $this->waitForText('Type d\'attribut', 30);
        $this->fillSelect('#product_attribute_create_reusable_attributeType', $attributeType, true);

        $this->click('div.next.step');

        $this->waitForText('Valeur', 30);
        $this->fillField('//input[@id="product_attribute_create_reusable_value"]', $value);
        $this->click('//button[@type="submit"]');
        $this->waitForText($attributeType, 30);
        $this->waitForText($value, 30);
        $this->stdCheck();
    }

    public function listAttributeTypes()
    {
        $this->amOnPage($this->getSilUrl() . '/product/attribute_type/list');
        $this->stdCheck();
        $this->waitForText('Types d\'attributs', 30);
    }

    public function deleteAttributeType($type)
    {
        $this->amOnPage($this->getSilUrl() . '/product/attribute_type/list');
        $this->waitForText($type);
        $this->stdCheck();
        $this->click('//span[contains(text(), "' . $type . '")]/ancestor::tr/descendant::a[contains(@class, "primary")]');
        $this->click("//span[contains(text(), 'Actions')]");
        $this->click('Supprimer ce type d\'attribut');
        $this->click('//a[@id="confirm-button"]');
        $this->waitForText('Type d\'attribut supprimé avec succès');
        $this->stdCheck();
    }
}
