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

class Ecommerce extends Lisem
{
    public function createShopCategory($categoryName)
    {
        /* Category submit does not work yet */
    }

    public function createChannel($channelName)
    {
        $this->amGoingTo('Create Channel ' . $channelName);
        $this->amOnPage('/lisem/librinfo/ecommerce/channel/create');

        $this->fillField("//input[contains(@id,'_name')]", $channelName);
        $this->fillField(
            "//input[contains(@id,'_code')]",
            /* @todo we should have a generate code on each admin */
            strtoupper(substr($channelName, 0, 1)) . ($this->getRandNbr() % 100)
        );
        $this->fillField("//input[contains(@id,'_hostname')]", '127.0.0.1');
        $this->fillField("//input[contains(@id,'_contactEmail')]", '127.0.0.1');
        $this->selectDrop('_themeName', 'Sylius LiSem');
        $this->clickCreate();
    }

    public function activeAccount($userLogin)
    {
        $this->amGoingTo('Active Shop User Account ' . $userLogin);
        $this->amOnPage('/lisem/librinfo/ecommerce/shop_user/list');

        $this->filterList($userLogin, 'username');

        $this->click('Éditer');
        //$this->click('ins.iCheck-helper');
        $this->clickCheckbox('enabled');

        $this->clickCreate('btn_update_and_list');
    }

    public function checkOrder($customerName)
    {
        $this->amGoingTo('CheckCmd');
        $this->amOnPage('/lisem/librinfo/ecommerce/order/list');

        $this->filterList($customerName, 'fulltextName');
        $this->click("(//a[contains(@href, '/show')])");
        $this->click("Liste d'actions");
        $this->click('Retourner à la liste');
        $this->waitForText('Liste des commandes', 30); // secs
    }
}
