<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

// @group login
// @group all

/* @todo: Should be renamed as Sil and add an empty extends as Lisem */

use Blast\Bundle\TestsBundle\Codeception\Step\Acceptance\Lisem as LisemTester;

$lisem = new LisemTester($scenario);
$lisem->loginLisem();
