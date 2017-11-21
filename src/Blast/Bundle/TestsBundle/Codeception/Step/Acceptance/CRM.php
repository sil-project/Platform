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

class CRM extends Lisem
{
    public function createCircle($groupName = 'SelGroup', $groupCode = 'SELGRP')
    {
        $this->amGoingTo('Create Circle ' . $groupName . '(' . $groupCode . ')');
        $this->amOnPage(constant('SILURL') . '/librinfo/crm/circle/list');
        $this->testLink('Ajouter', 'Nom');
        $this->fillField("//input[contains(@id, 'name')]", $groupName);
        $this->fillField("//input[contains(@id, 'code')]", $groupCode);
        $this->fillField("//textarea[contains(@id, 'description')]", 'Sel desc');
        $this->selectDrop('_type', 'Autres');
        $this->clickCreate();
        $this->waitForText('succès', 30); // secs
    }

    public function deleteCircle($filter = 'Sel')
    {
        $this->amGoingTo('Delete Circle ' . $filter);
        $this->amOnPage(constant('SILURL') . '/librinfo/crm/circle/list');
        $this->filterList($filter, 'name');
        // $this->testLink('Filtres');
        // $this->wait(1);
        // $this->click('i.fa.fa-square-o');
        // //$this->click("//ul[2]/li/ul/li/a/i");
        // $this->wait(1);
        // $this->click("//input[@id='filter_name_value']");
        // $this->fillField("//input[@id='filter_name_value']", $filter);
        // $this->click("//button[@type='submit']");
        // $this->wait(1);
        /* @todo check if it is the good default choice */
        $this->click('//label/div/ins');
        $this->click("//input[@value='OK']");
        $this->click("//button[@type='submit']");
        $this->waitForText('succès', 30); // secs
    }

    public function createCategory($selCat, $selCatParent = null)
    {
        $this->amGoingTo('Create Category ' . $selCat);

        $this->amOnPage(constant('SILURL') . '/librinfo/crm/category/list');
        $this->testLink('Ajouter', 'Nom');

        $this->fillField("//input[contains(@id,'name')]", $selCat);
        if (isset($selCatParent)) {
            $this->selectDrop('_treeParent', 'SelCatParent');
        }
        $this->clickCreate();
        $this->waitForText('succès', 30); // secs
    }

    public function deleteCategory($filter = 'Sel')
    {
        $this->amGoingTo('Delete Category ' . $filter);
        $this->amOnPage(constant('SILURL') . '/librinfo/crm/category/list');
        $this->filterList($filter, 'name');

        // $this->testLink('Filtres');
        // $this->wait(1);
        // $this->click('i.fa.fa-square-o');
        // $this->wait(1);
        // $this->click("//input[@id='filter_name_value']");
        // $this->fillField("//input[@id='filter_name_value']", $filter);
        // $this->click("//button[@type='submit']");
        /* @todo check if it is the good default choice */
        $this->click('//label/div/ins');
        $this->click("//input[@value='OK']");
        $this->click("//button[@type='submit']");
        $this->waitForText('succès', 30); // secs
    }
}
