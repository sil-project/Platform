<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\TestsBundle\Codeception\Step\Acceptance;

class SilUser extends Sil
{
    public function loggedAs($name)
    {
        $this->amGoingTo('Check that I am logged in as user ' . $name);
        $this->waitforText($name, 30);
    }

    public function createUser($username = 'sel-user', $email = 'sel-user@sil.eu', $password = 'sel-user')
    {
        $this->amGoingTo('Create User ' . $username . '( with email' . $email . ' and password ' . $password . ')');
        $this->amOnPage($this->getSilUrl() . '/user/list');
        $this->click("//span[contains(text(), 'Actions')]");
        $this->testLink('Ajouter', 'Nom');
        $this->fillField("//input[contains(@id, 'create_user_username')]", $username);
        $this->fillField("//input[contains(@id, 'create_user_email')]", $email);
        $this->fillField("//input[contains(@id, 'create_user_password')]", $password);

        $this->clickCreate();
        $this->waitForText('Création Réussie', 30); // secs
    }
}
