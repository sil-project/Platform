<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\TestsBundle\Codeception\Step\Acceptance;

/** @todo: should use traits or something for all those click and wait tools */
class Sil extends Common
{
    private $baseurl = null;

    protected function getSilUrl()
    {
        if (!isset($this->baseurl)) {
            if (!getenv('SILURL')) {
                die("Env Var SILURL is mandatory \n export SILURL='/sil'");
            }
            $this->baseurl = getenv('SILURL');
        }

        return  $this->baseurl;
    }

    /** @todo and find a clean way to auto login as admin for any app */
    public function loginSil($username, $password)
    {
        $this->amGoingTo('Test Login');
        $this->amOnPage($this->getSilUrl() . '/login');
        $this->waitForText('Courriel', 30);
        $this->waitForText('Mot de passe', 30);
        $this->fillField("//input[@id='_username']", $username);
        $this->fillField("//input[@id='_password']", $password);
        $this->click("//button[@type='submit']");
        $this->waitForText('Libre', 30);
        // $this->amOnPage($this->getSilUrl() . '/dashboard'); // all steps should call amOnPage before doing any actions
        //$this->hideSymfonyToolBar(); //useless for test and may hide important element
    }

    public function logoutSil()
    {
        //$this->scrollUp(); // logout is on top of page
        $this->amOnPage($this->getSilUrl() . '/dashboard');
        $this->click('li.dropdown.user-menu a');
        $this->waitForElementVisible('.dropdown-menu.dropdown-user', 30);
        $this->testLink('Déconnexion', 'Login');
    }

    public function testLink($linkName, $linkRes = null)
    {
        $linkRes = (isset($linkRes)) ? $linkRes : $linkName;
        $this->waitForText($linkName, 30); // secs
        $this->click($linkName);
        $this->waitForText($linkRes, 30); // secs
    }

    public function clickCreate($name = 'btn_create_and_list')
    {
        /* @todo: do the same for confirm action and for list batch action button */
        $this->scrollDown(); // submit button is on bottom of page
        // $this->scrollTo("//button[@name='" . $name . "']"); //, 10, 10);
        $this->click("//button[@name='" . $name . "']");
        //$this->waitForText('succès', 30); // secs
    }

    public function waitCube($class = 'sk-folding-cube')
    {
        $this->wait(1);
        $this->waitForElementNotVisible('.' . $class, 30);
    }

    public function generateCode($linkId = 'code_generate_code', $inputId = '_code')
    {
        // Sometime generate code don't work as expected, so we prefil the form
        $this->fillField(
            '//input[contains(@id, "' . $inputId . '")]',
            str_pad(substr('S' . $this->getRandNbr(), 0, 2), 3, '0')
        );
        $this->click("//a[contains(@id, '" . $linkId . "')]");
        $this->waitCube();
        $this->wait(1);
    }

    public function filterList($filter, $type = 'name')
    {
        //$this->scrollUp(); // Filter should be on top of page
        $this->waitForText('Filtres', 30); // secs
        $this->click('Filtres');
        $this->waitForElementVisible('.sonata-toggle-filter', 30);
        $this->click('a[filter-target$="' . $type . '"]');
        $this->waitForElementVisible('.sonata-filter-form', 30);
        $this->click('//input[contains(@id,"' . $type . '")]'); // Generic 'input[id^="filter_"][id$="_value"]' not always work
        $this->fillField('//input[contains(@id,"' . $type . '")]', $filter);
        $this->click('Filtrer');
    }

    public function selectSearchDrop($id, $value)
    {
        // UGLY FIRST WORKING WAY
        //$this->clickWithLeftButton('div[id^="s2id_"][id$="_producer_autocomplete_input"] a');
        //$this->pressKey('#s2id_autogen8_search', 'sel'); // ugly working way

        // UGLY SECOND WORKING WAY
        // $this->scrollTo('div[id^="s2id_"][id$="' . $id . '"] a', 0, -100);
        //$this->clickWithLeftButton('div[id^="s2id_"][id$="' . $id . '"] a');
        $this->waitForElementVisible('div[id^="s2id_"][id$="' . $id . '"] a');
        $this->click('div[id^="s2id_"][id$="' . $id . '"] a');
        // $this->scrollTo('//div[@id="select2-drop"]/div/input[contains(@id,"_search")]'); // is it in the footer ? moved by js ? webdriver is not aware of this move ?
        $this->waitForElementVisible('//div[@id="select2-drop"]/div/input[contains(@id,"_search")]', 30);
        $this->fillField('//div[@id="select2-drop"]/div/input[contains(@id,"_search")]', $value);
        $this->waitCube();
        //$this->clickWithLeftButton('//div[@id="select2-drop"]/ul/li/div/div[contains(string(), "' . $value . '")]');
        $this->waitForElementVisible('//div[@id="select2-drop"]/ul/li/div/div[contains(string(), "' . $value . '")]');
        $this->click('//div[@id="select2-drop"]/ul/li/div/div[contains(string(), "' . $value . '")]');
        $this->waitCube();
    }

    public function selectDrop($id, $value, $tag = 'a')
    {
        /* @todo test if there is more than one select on the page */
        // REAL example to click select2 elements below
        //$this->clickWithLeftButton('div[id^="s2id_"][id$="' . $id . '"] ' . $tag . '');
        $this->waitForElementVisible('div[id^="s2id_"][id$="' . $id . '"] ' . $tag . '');
        $this->click('div[id^="s2id_"][id$="' . $id . '"] ' . $tag . '');
        /* @todo maybe move text to string */
        //$this->clickWithLeftButton('//div[@id="select2-drop"]/ul/li/div[text()="' . $value . '"]');
        $this->waitForElementVisible('//div[@id="select2-drop"]/ul/li/div[text()="' . $value . '"]');

        // Little hack to scroll to element to be selected before clicking it
        $this->executeJS("jQuery('#select2-drop .select2-results').scrollTo(jQuery('#select2-drop .select2-results').find('.select2-result-label:contains(\"$value\")').position().top);");
        $this->wait(1);

        $this->click('//div[@id="select2-drop"]/ul/li/div[text()="' . $value . '"]');
        $this->waitCube();
    }

    public function clickCheckbox($name, $value = '1')
    {
        //$this->clickWithLeftButton('input[type="checkbox"][name$="[' . $name . ']"][value="' . $value . '"] + ins');
        $this->click('input[type="checkbox"][name$="[' . $name . ']"][value="' . $value . '"] + ins');
    }

    public function clickRadio($name, $value = '1')
    {
        //$this->clickWithLeftButton('input[type="radio"][name$="[' . $name . ']"][value="' . $value . '"] + ins');
        $this->click('input[type="radio"][name$="[' . $name . ']"][value="' . $value . '"] + ins');
    }
}
