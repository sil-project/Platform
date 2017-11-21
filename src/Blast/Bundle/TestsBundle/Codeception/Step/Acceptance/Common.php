<?php

/*
 * This file is part of the Lisem Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Step\Acceptance;

/** @todo find a clean way to extends the good configured class in codeception config suite file */
class Common extends \WebGuy
{
    public $randNbr = null;
    public $randName = null;

    public function setRand($new = false)
    {
        if ($new || !isset($this->randNbr)) {
            $this->randNbr = rand(1, 10000);
            $this->randName = 'sel-' . $this->randNbr;
        }
    }

    public function getRandNbr($new = false)
    {
        $this->setRand($new);

        return $this->randNbr;
    }

    public function getRandName($new = false)
    {
        $this->setRand($new);

        return $this->randName;
    }

    public function hideSymfonyToolBar()
    {
        // if (strpos('/', $this->grabFromCurrentUrl()) !== false) {
        //     $this->amOnPage('/');
        //     $this->wait(2);
        // }

        // Dirty Hack Check if there is some sfToolbar
        // if (count($this->grabMultiple("//div[contains(@id, 'sfToolbarMainContent')]") > 0)) {
        //     $style = $this->grabAttributeFrom("//div[contains(@id, 'sfToolbarMainContent')]", 'style');
        //     // Dirty Hack Check if it is visible
        //     if (strpos('display: block;', $style) !== false) {
        //         $this->click(['css' => '.hide-button']);
        //     }
        // }
        // Dirty Hack as even with try catch it create some failed output (on screen and in _output dir)
        try {
            $this->seeElement('.hide-button');
            $this->click(['css' => '.hide-button']);
        } catch (Exception $e) {
        }

        return true;
    }

    public function scrollDown()
    {
        $this->executeJS('window.scrollTo(0, document.body.scrollHeight);');
        $this->wait(1); // ah ah js tempo
    }

    public function scrollUp()
    {
        $this->executeJS('window.scrollTo(0, 0);');
        $this->wait(1); // ah ah js tempo
    }
}
