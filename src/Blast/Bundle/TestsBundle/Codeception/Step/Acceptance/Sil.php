<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
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

    public function stdCheck()
    {
        $this->cantSee('Stack Trace'); /* :) :) we hope so */
        $this->cantSeeInSource('<div class="exceptionContainer">'); /* :) :) we hope so too */
    }

    /** @todo and find a clean way to auto login as admin for any app */
    public function loginSil($username, $password)
    {
        $this->amGoingTo('Test Login');
        $this->amOnPage($this->getSilUrl() . '/login');
        $this->stdCheck();

        $this->waitForText('Courriel', 30);
        $this->waitForText('Mot de passe', 30);
        $this->fillField("//input[@id='_username']", $username);
        $this->fillField("//input[@id='_password']", $password);
        $this->click("//button[@type='submit']");
        $this->stdCheck();

        //$this->amOnPage($this->getSilUrl() . '/dashboard'); // all steps should call amOnPage before doing any actions
        //$this->hideSymfonyToolBar(); //useless for test and may hide important element
    }

    public function logoutSil()
    {
        $this->scrollUp(); // logout is on top of page
        $this->click('#user-menu');
        $this->waitForElementVisible('.menu', 30);
        $this->testLink('Déconnexion', 'Login');
    }

    public function testLink($linkName, $linkRes = null)
    {
        $linkRes = (isset($linkRes)) ? $linkRes : $linkName;
        $this->waitForText($linkName, 30); // secs
        $this->click($linkName);
        $this->stdCheck();
        $this->waitForText($linkRes, 30); // secs
    }

    public function clickCreate()
    {
        /* @todo: do the same for confirm action and for list batch action button */
        $this->scrollDown(); // submit button is on bottom of page
        // $this->scrollTo("//button[@name='" . $name . "']"); //, 10, 10);
        $this->click("//button[@type='submit']");
        //$this->waitForText('succès', 30); // secs
        $this->stdCheck();
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

    /**
     * @param string $selector select element css selector
     * @param string $value    the value to set
     */
    public function fillSelect($selector, $value)
    {
        $this->executeJs("jQuery('" . $selector . "').parents('.dropdown').dropdown('show');");
        $this->waitForText($value);
        $this->click('//div[contains(text(), "' . $value . '")]');
        $this->waitForText($value);
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
