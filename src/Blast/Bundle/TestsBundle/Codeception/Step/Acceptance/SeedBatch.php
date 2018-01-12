<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\TestsBundle\Codeception\Step\Acceptance;

class SeedBatch extends Lisem
{
    public function createPlot($producerName)
    {
        $plotName = $this->getRandName() . '-plot';

        $this->amGoingTo('Create Plot ' . $plotName);
        $this->amOnPage($this->getSilUrl() . '/seed_batch/plot/create');
        $this->fillField("//input[contains(@id,'_name')]", $plotName);
        $this->selectSearchDrop('_producer_autocomplete_input', $producerName);
        // $this->fillField("//input[contains(@id,'_city')]", $plotName . '-city');
        $this->selectSearchDrop('_city_autocomplete_input', $plotName . '-city');
        // $this->fillField("//input[contains(@id,'_zip')]", $this->getRandNbr());
        $this->selectSearchDrop('_zip_autocomplete_input', $this->getRandNbr());
        $this->generateCode();
        $this->clickCreate();
        $this->waitForText('succès', 30); // secs
        return $plotName;
    }

    /* @todo add a test for organism (not individual) */
    public function createProducer()
    {
        $producerName = $this->getRandName() . '-producer';

        $this->amGoingTo('Create a producer ' . $producerName);
        // @todo: fix URL (removing /sil)
        $this->amOnPage($this->getSilUrl() . '/seed_batch/seed_producer/create');
        //$this->click("//li[2]/div/label/div/ins"); //ugly work
        /* @todo: find a way to click without use li[2] ... */
        $this->click("//ul[contains(@id, '_isIndividual')]/li[2]/div/label/div/ins"); //ugly too
        $this->selectDrop('_title', 'Mme');
        $this->fillField("//input[contains(@id,'_firstname')]", $producerName);
        $this->fillField("//input[contains(@id,'_lastname')]", 'last-' . $producerName);
        $this->fillField("//input[contains(@id,'_email')]", $producerName . '@lisem.eu');
        $this->clickCreate();
        $this->waitForText('succès', 30); // secs
        return $producerName;
    }

    public function createSeedBatch($varietyName, $producerName, $plotName)
    {
        $seedBatchName = $this->getRandName() . '-seedbatch';

        $this->amGoingTo('Create Seed Batch' . $seedBatchName);
        $this->amOnPage($this->getSilUrl() . '/seed_batch/create');
        $this->selectSearchDrop('_variety_autocomplete_input', $varietyName);
        $this->selectSearchDrop('_producer_autocomplete_input', $producerName);
        $this->fillField("//input[contains(@id,'_productionYear')]", '1913');
        $this->fillField("//input[contains(@id,'_batchNumber')]", 1 + $this->getRandNbr() % 99);
        $this->selectSearchDrop('_plot_autocomplete_input', $plotName);
        $this->fillField("//textarea[contains(@id,'description')]", $seedBatchName . '-desc');
        $this->scrollUp(); // code generator is on top of page
        $this->generateCode();
        $this->clickCreate();
        $this->waitForText('succès', 30); // secs
        return $seedBatchName;
    }
}
