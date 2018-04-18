<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\TestsBundle\Codeception\Step\Acceptance;

class ProductOption extends Sil
{
    public function homePage()
    {
        $this->amOnPage($this->getSilUrl() . '/product/option_type');
        $this->stdCheck();
        $this->waitForText('Types d\'option', 30);
    }

    public function createOptionType($name = null)
    {
        if ($name === null) {
            $name = 'sil-product-option-type' . $this->getRandNbr();
        }

        $this->amOnPage($this->getSilUrl() . '/product/option_type/create');
        $this->stdCheck();
        $this->waitForText('Nouveau type d\'option', 30);
        $this->waitForText('Nom', 30);
        $this->fillField('//input[@id="product_option_type_create_name"]', $name);
        $this->click('//button[@type="submit"]');
        $this->waitForText('Types d\'option', 30);
        $this->waitForText($name, 30);
        $this->stdCheck();
        $this->cantSee('Créez un nouveau type d\'option');
    }

    public function createOption($optionType, $value = null)
    {
        if ($value === null) {
            $value = 'sil-product-option-value' . $this->getRandNbr();
        }

        $this->amOnPage($this->getSilUrl() . '/product/option/create');
        $this->stdCheck();
        $this->waitForText('Nouvelle option', 30);
        $this->waitForText('Type d\'option', 30);
        $this->fillSelect('#product_option_create_optionType', $optionType, true);

        $this->click('div.next.step');

        $this->waitForText('Valeur', 30);
        $this->fillField('//input[@id="product_option_create_value"]', $value);
        $this->click('//button[@type="submit"]');
        $this->waitForText($optionType, 30);
        $this->waitForText($value, 30);
        $this->stdCheck();
    }

    public function listOptiontypes()
    {
        $this->amOnPage($this->getSilUrl() . '/product/option_type/list');
        $this->stdCheck();
        $this->waitForText('Types d\'option', 30);
    }

    public function deleteOptionType($type)
    {
        $this->amOnPage($this->getSilUrl() . '/product/option_type/list');
        $this->waitForText($type);
        $this->stdCheck();
        $this->click('//span[contains(text(), "' . $type . '")]/ancestor::tr/descendant::a[contains(@class, "primary")]');
        $this->click("//span[contains(text(), 'Actions')]");
        $this->click('Supprimer ce type d\'option');
        $this->waitForText('Oui');
        $this->click('//a[@id="confirm-button"]');
        $this->waitForText('Type d\'option supprimé avec succès');
        $this->stdCheck();
    }
}
