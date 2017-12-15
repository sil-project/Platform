<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

// @group menu
// @group all

use Blast\Bundle\TestsBundle\Codeception\Step\Acceptance\Lisem as LisemTester;

$lisem = new LisemTester($scenario);
$lisem->loginLisem();

$lisem->wantTo('Click on Menu List');
$lisem->testLink('Contacts');
$lisem->testLink('Emailing');
$lisem->testLink('Èspèces');
$lisem->testLink('Variétés');
$lisem->testLink('Lots');
$lisem->testLink('Parcelles');
//$lisem->testLink('Tests de germination');
$lisem->testLink('Producteurs');
$lisem->click('Articles');
$lisem->testLink('Semences');
$lisem->testLink('Autres');

//$lisem->testLink('Conditionnement');
//$lisem->testLink('Inventaire');
//$lisem->testLink('Catalogues');
$lisem->testLink('Commandes');

$lisem->click('Gestion / Compta');
$lisem->testLink('Journal des ventes');
$lisem->testLink('Livre de caisse');
