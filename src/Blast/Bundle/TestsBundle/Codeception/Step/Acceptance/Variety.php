<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Step\Acceptance;

class Variety extends Lisem
{
    public function createVariety($speciesName, $plantCatName)
    {
        $varietyName = $this->getRandName() . '-variety';

        $this->amGoingTo('Create Variety ' . $varietyName);
        $this->amOnPage(constant('SILURL') . '/sil/variety/create');
        $this->fillField("//input[contains(@id,'name')]", $varietyName);
        $this->fillField("//input[contains(@id,'latin_name')]", 'latium-' . $varietyName);
        $this->selectDrop('_species', $speciesName);
        $this->selectDrop('_plant_categories', $plantCatName, 'ul');
        $this->click("//a[contains(@id, 'plant_type_add_choice')]/i");
        $this->fillField("//div[contains(@id,'popover')]/div[2]/div/form/div/div/div/input", $this->getRandName() . '-plant');
        $this->click("//div[contains(@id,'popover')]/div[2]/div/form/div/div/div[2]/button");
        $this->generateCode();
        $this->clickCreate();
        $this->waitForText('succès', 30); // secs
        return $varietyName;
    }

    public function createSpecies($genusName, $plantCatName)
    {
        $speciesName = $this->getRandName() . '-species-name';

        $this->amGoingTo('Create Species ' . $speciesName);
        $this->amOnPage(constant('SILURL') . '/sil/variety/species/create');
        $this->fillField("//input[contains(@id,'name')]", $speciesName);
        $this->selectDrop('_genus', $genusName);
        $this->fillField("//input[contains(@id,'latin_name')]", 'latium-' . $speciesName);
        $this->selectDrop('_plant_categories', $plantCatName, 'ul');
        $this->generateCode();
        $this->clickCreate();
        // $this->clickCreate('btn_create_and_edit');
        $this->waitForText('succès', 30); // secs
        return $speciesName;
    }

    public function createGenus($familyName)
    {
        $genusName = $this->getRandName() . '-genus';

        $this->amGoingTo('Create Genus ' . $genusName);
        $this->amOnPage(constant('SILURL') . '/sil/variety/genus/create');
        $this->fillField("//input[contains(@id,'name')]", $genusName);
        $this->fillField("//textarea[contains(@id,'description')]", $genusName . '-desc');
        $this->selectDrop('_family', $familyName);
        $this->clickCreate();
        $this->waitForText('succès', 30); // secs
        return $genusName;
    }

    public function createFamily()
    {
        $familyName = $this->getRandName() . '-family';

        $this->amGoingTo('Create Family ' . $familyName);
        $this->amOnPage(constant('SILURL') . '/sil/variety/family/create');
        $this->fillField("//input[contains(@id,'name')]", $familyName);
        $this->fillField("//input[contains(@id,'latin_name')]", 'latium-' . $familyName);
        // $this->fillField("//textarea[contains(@id,'description')]", $familyName . '-desc');
        $this->clickCreate();
        $this->waitForText('succès', 30); // secs
        return $familyName;
    }

    public function createPlantCategory()
    {
        $plantCatName = $this->getRandName() . '-plant-cat';

        $this->amGoingTo('Create Plant Category ' . $plantCatName);
        $this->amOnPage(constant('SILURL') . '/sil/variety/plantcategory/create');
        $this->fillField("//input[contains(@id,'name')]", $plantCatName);
        $this->fillField("//input[contains(@id,'code')]", $this->getRandNbr());
        $this->clickCreate();
        $this->waitForText('succès', 30); // secs
        return $plantCatName;
    }
}
