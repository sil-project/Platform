<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

if (!getenv('SILURL')) {
    die('Env Variable SILURL is mandatory');
    //putenv('SILURL', '/sil');
}
//Codeception\Util\Autoload::addNamespace('Step', __DIR__ . '../../Blast/Bundle/TestsBundle/Codeception/Step/');

/* @warning it look like it does not work with relative path */

Codeception\Util\Autoload::addNamespace('Step\\', 'src/Blast/Bundle/TestsBundle/Codeception/Step');
