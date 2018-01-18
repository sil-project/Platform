<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

// @group user

namespace Blast\Bundle\TestsBundle\Codeception\Step\Acceptance;

// @TODO: sould use Sil (Not Lisem)
class SonataSyliusUser extends Lisem
{
    public function createUser($username = 'sel-user', $email = 'sel-user@lisem.eu', $password = 'sel-user')
    {
        $this->amGoingTo('Create User ' . $username . '( with email' . $email . ' and password ' . $password . ')');
        $this->amOnPage($this->getSilUrl() . '/user/user/list');
        $this->testLink('Ajouter', "Nom d'utilisateur");
        $this->fillField("//input[contains(@id, 'username')]", $username);
        $this->fillField("//input[contains(@id, 'firstName')]", $username . '-first');
        $this->fillField("//input[contains(@id, 'lastName')]", $username . '-last');
        $this->fillField("//input[contains(@id, 'email')]", $email);
        $this->clickCheckbox('enabled');
        $this->fillField("//input[contains(@id, 'plainPassword_first')]", $password);
        $this->fillField("//input[contains(@id, 'plainPassword_second')]", $password);

        $this->clickCreate();
        $this->waitForText('succès', 30); // secs
    }

    public function loggedAs($email)
    {
        $this->amGoingTo('Check that I am logged in as user ' . $email);
        $this->amOnPage($this->getSilUrl() . '/dashboard');
        $this->click('li.dropdown.user-menu a');
        $this->waitforText($email, 30);
    }
}
