<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

// @group crm
// @group all

use Blast\Bundle\TestsBundle\Codeception\Step\Acceptance\CRM as CRMTester;
use Blast\Bundle\TestsBundle\Codeception\Step\Acceptance\Lisem as LisemTester;

$lisem = new LisemTester($scenario);
$lisem->loginLisem();
$crm = new CRMTester($scenario);

$crm->wantTo('Create and delete category');
$cat = 'SelCat';
$catParent = 'SelCatParent';

$crm->createCategory($catParent);
$crm->createCategory($cat, $catParent);
$crm->deleteCategory();
$crm->createCategory($catParent);
$crm->createCategory($cat, $catParent);
