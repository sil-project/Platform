<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Step\Acceptance;

class Lisem extends SilWebApp
{
    public function loginLisem($username = 'lisem@lisem.eu', $password = 'lisem')
    {
        $this->loginSil($username, $password);
    }

    public function logoutLisem()
    {
        $this->logoutSil();
    }
}
